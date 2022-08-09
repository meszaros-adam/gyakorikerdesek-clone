<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ["question", "description", "user_id", "category_id"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
