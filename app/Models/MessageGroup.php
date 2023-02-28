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
        return $this->belongsTo(GroupDetail::class, 'group_id', 'id');
    }


    public function messages ()
    {
        return $this->belongsTo(Message::class, 'group_id', 'id');
    }
}
