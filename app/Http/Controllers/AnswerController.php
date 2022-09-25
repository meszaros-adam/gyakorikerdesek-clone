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

        $answer =  Answer::create([
            "answer" => $request->answer,
            "question_id" => $request->question_id,
            "user_id" => Auth::user()->id,
        ]);

        return $answer->load('user');
    }
    public function get(Request $request){
        return Answer::orderBy($request->orderBy, $request->ordering)->with('user')->paginate($request->itemPerPage);

    }
}
