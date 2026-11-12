<?php

namespace Tests\Feature;

use App\Livewire\ChatWidget;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotQuestion;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\Product;
use App\Services\Chatbot\ChatbotReply;
use App\Models\ClientMessage;
use App\Models\User;
use App\Services\Chatbot\ChatbotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token'      => 'TOKEN',
            'services.telegram.chat_id'        => '999',
            'services.telegram.webhook_secret' => null,
        ]);

        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
    }

    private function catalog(): Product
    {
        $cat = Category::create(['slug' => 'receipt-printers']);
        $cat->translations()->createMany([
            ['lang' => 'uz', 'name' => 'Chek printerlar'],
            ['lang' => 'ru', 'name' => 'Принтеры чеков'],
        ]);
        $p = Product::create(['category_id' => $cat->id, 'slug' => 'xp-q890k', 'model_number' => 'XP-Q890K']);
        $p->translations()->createMany([
            ['lang' => 'uz', 'name' => 'Chek printeri XP-Q890K', 'description' => '80 mm'],
            ['lang' => 'ru', 'name' => 'Чековый принтер XP-Q890K', 'description' => '80 мм'],
        ]);

        $seller = Company::create([
            'name' => 'OOO Seller', 'brand' => 'Q Seller', 'inn' => '111', 'slug' => 'q-seller', 'phone' => '+998901112233',
            'types' => ['retail', 'service'], 'status' => 'approved', 'vat_status' => 'non_payer', 'manufacturer_status' => 'none',
        ]);
        CompanyProduct::create(['company_id' => $seller->id, 'product_id' => $p->id, 'is_available' => true]);

        return $p;
    }

    private function ask(string $q, array $history = []): ChatbotReply
    {
        return app(ChatbotService::class)->respond($q, 'web', null, $history);
    }

    public function test_product_card_by_model_number_in_both_languages(): void
    {
        $this->catalog();

        $uz = $this->ask('q890k haqida');
        $this->assertTrue($uz->answered);
        $this->assertStringContainsString('Chek printeri XP-Q890K', $uz->text);
        $this->assertStringContainsString('/catalog/receipt-printers/xp-q890k', $uz->text);

        $ru = $this->ask('Расскажите про XP-Q890K');
        $this->assertSame('ru', $ru->lang);
        $this->assertStringContainsString('Чековый принтер XP-Q890K', $ru->text);
    }

    public function test_price_question_lists_sellers_without_prices(): void
    {
        $this->catalog();

        $reply = $this->ask('Сколько стоит XP-Q890K?');

        $this->assertStringContainsString('Q Seller', $reply->text);
        $this->assertStringContainsString('/partners/q-seller', $reply->text);
    }

    public function test_follow_up_uses_product_from_history(): void
    {
        $this->catalog();

        $reply = $this->ask('narxi qancha?', [['role' => 'user', 'content' => 'XP-Q890K haqida']]);

        $this->assertStringContainsString('Q Seller', $reply->text);
    }

    public function test_dealer_registration_answer(): void
    {
        $reply = $this->ask("Qanday qilib diler bo'lish mumkin?");

        $this->assertTrue($reply->answered);
        $this->assertStringContainsString(route('register'), $reply->text);
    }

    public function test_taught_answer_wins(): void
    {
        ChatbotKnowledge::create(['question' => 'Qog\'oz lentasi sotasizmi?', 'answer' => 'Ha, chek lentasi dilerlarda bor', 'is_active' => true]);

        $reply = $this->ask('chek uchun qogoz lentasi sotasizmi');

        $this->assertSame('Ha, chek lentasi dilerlarda bor', $reply->text);
    }

    public function test_unknown_question_is_escalated_to_admin_telegram(): void
    {
        $reply = $this->ask('Kosmosga raketa uchadimi?');

        $this->assertFalse($reply->answered);
        $this->assertDatabaseHas('chatbot_questions', ['question' => 'Kosmosga raketa uchadimi?', 'channel' => 'web', 'status' => 'open']);

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'api.telegram.org')
            && $r['chat_id'] === '999'
            && str_contains($r['text'], 'Kosmosga raketa uchadimi?'));
    }

    public function test_handoff_words_go_to_admin(): void
    {
        $reply = $this->ask('Позовите оператора');

        $this->assertFalse($reply->answered);
        $this->assertSame(1, ChatbotQuestion::count());
    }

    public function test_telegram_text_gets_bot_reply(): void
    {
        $user = User::factory()->create(['role' => 'client', 'telegram_chat_id' => '555']);

        $this->postJson(route('telegram.webhook'), [
            'message' => ['chat' => ['id' => 555], 'from' => ['id' => 555], 'text' => 'Salom'],
        ])->assertNoContent();

        $this->assertDatabaseHas('client_messages', ['user_id' => $user->id, 'sender' => 'client', 'body' => 'Salom']);
        $this->assertDatabaseHas('client_messages', ['user_id' => $user->id, 'sender' => 'bot']);

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'sendMessage') && $r['chat_id'] === '555');
    }

    public function test_bot_stays_quiet_while_admin_is_chatting(): void
    {
        $user = User::factory()->create(['role' => 'client', 'telegram_chat_id' => '555']);
        ClientMessage::create(['user_id' => $user->id, 'sender' => 'admin', 'body' => 'Assalomu alaykum']);

        $this->postJson(route('telegram.webhook'), [
            'message' => ['chat' => ['id' => 555], 'from' => ['id' => 555], 'text' => 'Rahmat'],
        ])->assertNoContent();

        $this->assertDatabaseMissing('client_messages', ['sender' => 'bot']);
    }

    public function test_admin_answer_replies_on_telegram_and_teaches_bot(): void
    {
        $admin  = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client', 'telegram_chat_id' => '555']);
        $q = ChatbotQuestion::create(['user_id' => $client->id, 'channel' => 'telegram', 'lang' => 'uz', 'question' => 'Kafolat necha oy?']);

        $this->actingAs($admin)
            ->post(route('admin.chatbot.questions.answer', $q), ['answer' => '12 oy rasmiy kafolat', 'teach' => '1'])
            ->assertRedirect(route('admin.chatbot.index'));

        $this->assertSame('answered', $q->fresh()->status);
        $this->assertDatabaseHas('chatbot_knowledge', ['question' => 'Kafolat necha oy?', 'answer' => '12 oy rasmiy kafolat']);
        Http::assertSent(fn (Request $r) => $r['chat_id'] === '555' && $r['text'] === '12 oy rasmiy kafolat');
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $q = ChatbotQuestion::create(['channel' => 'web', 'lang' => 'ru', 'question' => 'Где драйвер?']);

        $this->actingAs($admin)->get(route('admin.chatbot.index'))->assertOk()->assertSee('Где драйвер?');
        $this->actingAs($admin)->get(route('admin.chatbot.questions.show', $q))->assertOk();
        $this->actingAs($admin)->get(route('admin.chatbot.knowledge.create'))->assertOk();
    }

    public function test_web_widget_round_trip(): void
    {
        $this->catalog();

        Livewire::test(ChatWidget::class)
            ->set('input', 'Расскажите про XP-Q890K')
            ->call('send')
            ->assertSet('pending', true)
            ->assertDispatched('chatbot-pending')
            ->call('answer')
            ->assertSet('pending', false)
            ->assertSee('Чековый принтер XP-Q890K');
    }
}
