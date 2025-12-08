<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'total_price', 'items_json', 'status'];

    protected $casts = [
        'total_price' => 'decimal:2',
        'items_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
