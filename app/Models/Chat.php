<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'message', 'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function isUnread()
    {
        return is_null($this->read_at);
    }
}
