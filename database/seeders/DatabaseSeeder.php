<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate([
            'nim' => '2301001',
        ], [
            'nama' => 'Admin TemuIN',
            'password' => Hash::make('temuin123'),
        ]);

        $user = User::query()->updateOrCreate([
            'nim' => '2301002',
        ], [
            'nama' => 'Nabila Putri',
            'password' => Hash::make('kampus123'),
        ]);

        Item::query()->delete();

        Item::query()->create([
            'user_id' => $admin->id,
            'nama' => 'Kartu Tanda Mahasiswa',
            'deskripsi' => 'KTM atas nama Nabila Putri ditemukan dekat parkiran motor fakultas teknik.',
            'lokasi' => 'Parkiran Fakultas Teknik',
            'kontak' => '081234567890',
            'status' => 'ditemukan',
        ]);

        Item::query()->create([
            'user_id' => $user->id,
            'nama' => 'Dompet Cokelat',
            'deskripsi' => 'Dompet kulit berisi kartu debit dan uang tunai. Hilang setelah kelas siang.',
            'lokasi' => 'Gedung C Lantai 2',
            'kontak' => '081212345678',
            'status' => 'hilang',
        ]);
    }
}
