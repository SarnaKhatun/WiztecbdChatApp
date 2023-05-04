<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
    ];

    public function group()
    {
        return $this->belongsTo(MessageGroup::class);
    }


    public function messages()
    {
        return $this->hasOne(Message::class,'id', 'group_id')->with(['groupDetails', 'messageDetails']);
    }

}
