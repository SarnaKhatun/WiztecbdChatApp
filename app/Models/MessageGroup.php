<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_name',
    ];


    public function groupDetail ()
    {
        return $this->hasMany(GroupDetail::class, 'group_id', 'id');
    }


    public function messages ()
    {
        return $this->hasMany(Message::class, 'group_id', 'id')->with('messageDetails');
    }
}
