<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|min:6',
            'category_id' => 'required|numeric',
        ]);

        return Question::create([
            'question' => $request->question,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
        ]);
    }
    public function getLastFive()
    {
        return Question::latest()->take(5)->with('category')->get();
    }
    public function getSingle(Request $request)
    {
        $question =  Question::with('user')->find($request->id);
        $answers = Answer::where('question_id', $request->id)->paginate(10);

        return response()->json([
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
