<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;

class Answer extends Model
{
    use HasFactory;
    use Rateable;

    protected $fillable = ['answer', 'question_id', "user_id"];

    protected $with = ["user"];

    protected $appends = ['average_rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //reformat time to readable
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
    public function getAverageRatingAttribute()
    {
        return intval($this->averageRating());
    }
}
