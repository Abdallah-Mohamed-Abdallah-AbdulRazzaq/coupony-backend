<?php

namespace App\Domain\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class StoreVerification extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'store_verifications';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'store_id',
        'document_type',
        'document_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($verification) {
            if (empty($verification->id)) {
                $verification->id = (string) Str::uuid();
            }
        });
    }

    /**
     * A verification belongs to a store
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
