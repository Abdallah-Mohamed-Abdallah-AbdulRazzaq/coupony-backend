<?php

namespace App\Domain\User\Models;

use App\Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreFollowers extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'notification_enabled',
        'followed_at',
    ];

    protected $casts = [
        'followed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}