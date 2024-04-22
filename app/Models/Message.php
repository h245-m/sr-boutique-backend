<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'text',
        'user_id'
    ];

    public function chat_room(){
        return $this->belongsTo(ChatRoom::class);
    }
}

