<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wish extends Model
{
    use HasFactory;

    /**
     * Kolum yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'name',
        'age',
        'is_adult',
        'message',
        'photo_path',
        'amount',
        'is_rolled',
    ];

    /**
     * Cast type untuk kolum
     */
    protected $casts = [
        'is_rolled' => 'boolean',
        'is_adult'  => 'boolean',
        'amount'    => 'integer',
    ];

    public const ADULT_MESSAGES = [
    'Dah besar dah! Tahun depan cuba jadi pengagih pula! 😂',
    'Maaf ye, duit raya untuk budak-budak je. Awak kena bagi duit raya sekarang! 😆',
    'Umur dah mencecah 20, kena kerja sendiri cari duit raya! 💪',
    'Hehe, next year jadi uncle/auntie yang bagi duit raya pula ye! 🧧',
    'Tahniah! Awak dah graduate dari dapat duit raya! 🎓😂',
    ];
    /**
     * Pool jumlah duit raya virtual (RM)
     * Boleh ubah ikut citarasa
     */
    public const AMOUNT_POOL = [ 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 7, 7, 7, 8, 8, 9, 10, 20, 50];

    /**
     * Semak sama ada wish ni dah dapat duit raya
     */
    public function hasReceived(): bool
    {
        return $this->is_rolled && $this->amount !== null;
    }

    /**
     * Return URL gambar — guna placeholder kalau tiada gambar
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }

        return asset('images/placeholder-raya.png');
    }

    /**
     * Format amount dengan "RM" prefix
     */
    public function getFormattedAmountAttribute(): string
    {
        if ($this->amount === null) {
            return '???';
        }

        return 'RM ' . $this->amount;
    }

    /**
     * Scope — wishes yang belum kena roll
     */
    public function scopeNotRolled($query)
    {
        return $query->where('is_rolled', false);
    }

    /**
     * Scope — wishes yang dah kena roll
     */
    public function scopeRolled($query)
    {
        return $query->where('is_rolled', true);
    }
}