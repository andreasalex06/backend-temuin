<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_item_list_and_search(): void
    {
        $this->seed();

        $this->getJson('/api/items')
            ->assertOk()
            ->assertJsonStructure(['data' => ['data']]);

        $this->getJson('/api/items/search?q=Dompet')
            ->assertOk()
            ->assertJsonCount(1, 'data.data');
    }

    public function test_guest_cannot_create_item(): void
    {
        $this->postJson('/api/items', [
            'nama' => 'Laptop',
            'deskripsi' => 'Hilang di kampus',
            'lokasi' => 'Gedung A',
            'kontak' => '0812',
            'status' => 'hilang',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_item(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Storage::fake('public');

        $this->withHeader('Accept', 'application/json')
            ->post('/api/items', [
                'nama' => 'Laptop',
                'deskripsi' => 'Hilang di kampus',
                'lokasi' => 'Gedung A',
                'kontak' => '0812',
                'status' => 'hilang',
                'image' => UploadedFile::fake()->image('laptop.jpg'),
            ])
            ->assertCreated()
            ->assertJsonPath('data.nama', 'Laptop')
            ->assertJsonPath('data.image_url', fn ($value) => str_contains((string) $value, '/storage/items/'));
    }

    public function test_user_cannot_delete_other_users_item(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $item = Item::query()->create([
            'user_id' => $owner->id,
            'nama' => 'Dompet',
            'deskripsi' => 'Tes',
            'lokasi' => 'Gedung B',
            'kontak' => '0812',
            'status' => 'hilang',
        ]);

        Sanctum::actingAs($intruder);

        $this->deleteJson("/api/items/{$item->id}")
            ->assertForbidden();
    }
}
