<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'nama', 'deskripsi', 'lokasi', 'kontak', 'status', 'image_path'])]
class Item extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public const STATUS_HILANG = 'hilang';
    public const STATUS_DITEMUKAN = 'ditemukan';
    public const STATUS_SELESAI = 'selesai';

    public const STATUSES = [
        self::STATUS_HILANG,
        self::STATUS_DITEMUKAN,
        self::STATUS_SELESAI,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->image_path ? '/storage/'.$this->image_path : null,
        );
    }
}
