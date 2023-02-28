<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
      'messages_id',
      'sender_id',
      'receiver_id',
      'message_body',
      'status',
    ];



    public function messages()
    {
        return $this->belongsTo(Message::class);
    }
}
