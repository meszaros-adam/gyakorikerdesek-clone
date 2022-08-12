<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Question extends Model
{
    use HasFactory; 

    protected $fillable = ["question", "description", "user_id", "category_id"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function answers(){
        return $this->hasMany(Answer::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    //reformat time to readable
    public function getCreatedAtAttribute($value){
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }

}
