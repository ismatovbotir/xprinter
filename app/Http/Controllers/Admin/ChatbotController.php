<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotQuestion;
use App\Models\ClientMessage;
use App\Services\Chatbot\KnowledgeBase;
use App\Services\TelegramBotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Unanswered bot questions + the Q&A the admin teaches the bot. */
class ChatbotController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab') === 'knowledge' ? 'knowledge' : 'questions';

        $questions = ChatbotQuestion::with('user')
            ->orderByRaw("status = 'open' desc")
            ->latest()
            ->paginate(30, ['*'], 'q_page');

        $knowledge = ChatbotKnowledge::latest()->paginate(30, ['*'], 'k_page');
        $openCount = ChatbotQuestion::where('status', 'open')->count();

        return view('admin.chatbot.index', compact('tab', 'questions', 'knowledge', 'openCount'));
    }

    public function showQuestion(ChatbotQuestion $question): View
    {
        return view('admin.chatbot.question', compact('question'));
    }

    /** Answer the client (Telegram) and, optionally, teach the bot the same answer. */
    public function answerQuestion(Request $request, ChatbotQuestion $question, TelegramBotService $telegram): RedirectResponse
    {
        $data = $request->validate([
            'answer'         => 'required|string|max:4000',
            'teach_question' => 'nullable|string|max:2000',
            'teach'          => 'nullable|boolean',
        ]);

        $question->update(['answer' => $data['answer'], 'status' => 'answered']);

        $user = $question->user;

        if ($question->channel === 'telegram' && $user?->telegram_chat_id) {
            ClientMessage::create([
                'user_id'  => $user->id,
                'admin_id' => $request->user()->id,
                'sender'   => 'admin',
                'body'     => $data['answer'],
                'is_read'  => true,
            ]);

            $telegram->sendMessage($user->telegram_chat_id, $data['answer']);
        }

        if ($request->boolean('teach')) {
            ChatbotKnowledge::create([
                'question' => ($data['teach_question'] ?? null) ?: $question->question,
                'answer'   => $data['answer'],
                'lang'     => null,
            ]);
            KnowledgeBase::forgetCache();
        }

        return redirect()->route('admin.chatbot.index')->with('success', 'Javob saqlandi');
    }

    public function destroyQuestion(ChatbotQuestion $question): RedirectResponse
    {
        $question->delete();

        return back()->with('success', "Savol o'chirildi");
    }

    public function createKnowledge(): View
    {
        return view('admin.chatbot.knowledge-form', ['entry' => new ChatbotKnowledge(['is_active' => true])]);
    }

    public function storeKnowledge(Request $request): RedirectResponse
    {
        ChatbotKnowledge::create($this->validated($request));
        KnowledgeBase::forgetCache();

        return redirect()->route('admin.chatbot.index', ['tab' => 'knowledge'])->with('success', "Bilim qo'shildi");
    }

    public function editKnowledge(ChatbotKnowledge $knowledge): View
    {
        return view('admin.chatbot.knowledge-form', ['entry' => $knowledge]);
    }

    public function updateKnowledge(Request $request, ChatbotKnowledge $knowledge): RedirectResponse
    {
        $knowledge->update($this->validated($request));
        KnowledgeBase::forgetCache();

        return redirect()->route('admin.chatbot.index', ['tab' => 'knowledge'])->with('success', 'Saqlandi');
    }

    public function destroyKnowledge(ChatbotKnowledge $knowledge): RedirectResponse
    {
        $knowledge->delete();
        KnowledgeBase::forgetCache();

        return back()->with('success', "O'chirildi");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => 'required|string|max:2000',
            'answer'   => 'required|string|max:4000',
            'lang'     => 'nullable|in:uz,ru',
        ]);

        return $data + ['is_active' => $request->boolean('is_active')];
    }
}
