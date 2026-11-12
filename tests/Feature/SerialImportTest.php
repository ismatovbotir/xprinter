<?php

namespace Tests\Feature;

use App\Models\ProductSerial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class SerialImportTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_producer_imports_csv_and_duplicates_are_skipped(): void
    {
        $producer = $this->makeProducer();
        $product  = $this->makeProduct();
        ProductSerial::create(['product_id' => $product->id, 'serial_number' => 'XP0001', 'status' => 'available']);

        $csv = UploadedFile::fake()->createWithContent('serials.csv', "XP0001\nXP0002\nXP0003\n\nXP0002\n");

        $this->actingAs($producer)
            ->post(route('producer.serials.store'), ['product_id' => $product->id, 'csv' => $csv])
            ->assertRedirect(route('producer.serials.index'))
            // XP0001 already existed, XP0002 appears twice → 2 created, 2 skipped
            ->assertSessionHas('success', fn ($msg) => str_contains($msg, '2 ta qo\'shildi') && str_contains($msg, '2 ta o\'tkazib'));

        $this->assertSame(3, ProductSerial::count());
        $this->assertDatabaseHas('product_serials', ['serial_number' => 'XP0003', 'status' => 'available', 'company_id' => null]);
        $this->assertDatabaseHas('activity_log', ['log_name' => 'serials', 'event' => 'created', 'causer_id' => $producer->id]);
    }

    public function test_import_requires_product_and_file(): void
    {
        $producer = $this->makeProducer();

        $this->actingAs($producer)
            ->post(route('producer.serials.store'), [])
            ->assertSessionHasErrors(['product_id', 'csv']);
    }

    public function test_dealers_cannot_import_serials(): void
    {
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();
        $csv     = UploadedFile::fake()->createWithContent('serials.csv', "XP9999\n");

        $this->actingAs($owner)
            ->post(route('producer.serials.store'), ['product_id' => $product->id, 'csv' => $csv])
            ->assertForbidden();

        $this->assertSame(0, ProductSerial::count());
    }

    public function test_index_filters_by_status(): void
    {
        $producer = $this->makeProducer();
        $product  = $this->makeProduct();
        ProductSerial::create(['product_id' => $product->id, 'serial_number' => 'AVAIL-1', 'status' => 'available']);
        ProductSerial::create(['product_id' => $product->id, 'serial_number' => 'SOLD-1',  'status' => 'sold']);

        $this->actingAs($producer)
            ->get(route('producer.serials.index', ['status' => 'sold']))
            ->assertOk()
            ->assertSee('SOLD-1')
            ->assertDontSee('AVAIL-1');
    }
}
