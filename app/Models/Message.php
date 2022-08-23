<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'sender_id', 'addressee_id'];

    protected $with = ["sender"];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id' );
    }
}
