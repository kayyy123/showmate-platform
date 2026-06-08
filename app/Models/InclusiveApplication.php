<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusiveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'disability_type',
        'identity_document',
        'support_document',
        'description',
        'status',
        'admin_note',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Store::class, 'id', 'id', 'store_id', 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
