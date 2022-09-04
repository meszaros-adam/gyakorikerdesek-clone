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
        return Question::latest()->take(5)->get();
    }
    public function getSingle(Request $request)
    {
        $question =  Question::find($request->id);
        $answers = Answer::where('question_id', $request->id)->paginate($request->itemPerPage);

        return response()->json([
            'question' => $question,
            'answers' => $answers,
        ]);
    }
    public function get(Request $request)
    {
        return Question::orderBy($request->orderBy, $request->ordering)->paginate($request->itemPerPage);
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'question' => 'required|min:6',
            'categoryId' => 'required|numeric',
            'userId'  => 'required|numeric',
        ]);

        return Question::where('id', $request->id)->update([
            'question' => $request->question,
            'description' => $request->description,
            'category_id' => $request->categoryId,
            'user_id' => $request->userId

        ]);
    }
    public function delete(Request $request){
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        return Question::where('id', $request->id)->delete();
    }
}
