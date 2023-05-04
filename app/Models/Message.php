<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'group_name',
        'sender_id',
        'receiver_id',
        'name',
        'image',
        'status',
    ];


    public function messageDetails()
    {
        return $this->hasMany(MessageDetail::class, 'messages_id', 'id');
    }

    public function groupDetails()
    {
        return $this->hasOne(MessageGroup::class, 'id', 'group_id');
    }


}
