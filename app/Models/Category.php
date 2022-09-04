<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    //mutator that set first character uppercase
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
