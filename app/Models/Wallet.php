<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;

    protected $fillable = [
        'address',
        'key',
        'user_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
