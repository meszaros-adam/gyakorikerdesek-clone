<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'message', 'sender_id', 'addressee_id'];

    protected $with = ["sender", 'addressee'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function addressee()
    {
        return $this->belongsTo(User::class, 'addressee_id');
    }
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
    public function getReadedAttribute($value)
    {
        return $value == 1 ? true : false;
    }
}
