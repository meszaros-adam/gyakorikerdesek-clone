<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function add(Request $request){
        $this->validate($request,[
            'question' =>'required|min:6',
            'description' =>'required',
        ]);

        return Question::create([
            'question' => $request->question,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

    }
}
