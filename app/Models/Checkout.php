<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_code',
        'user_id',
        'product_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'notes',
        'quantity',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'total_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Checkout $checkout) {
            if (empty($checkout->order_code)) {
                $checkout->order_code = static::generateUniqueOrderCode();
            }
        });
    }

    public static function generateUniqueOrderCode(): string
    {
        do {
            $code = 'ELK-' . str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
