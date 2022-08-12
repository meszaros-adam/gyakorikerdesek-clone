<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function add(Request $request){
        $this->validate($request,[
            "answer" => "required|min:2",
            "question_id" => "required",
        ]);

        return Answer::create([
            "answer" => $request->answer,
            "question_id" => $request->question_id,
            "user_id" => Auth::user()->id,
        ]);
    }
}
