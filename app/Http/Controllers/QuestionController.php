<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Message;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuestionController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|min:6',
            'category_id' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $question = Question::create([
                'question' => $request->question,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'category_id' => $request->category_id,
            ]);

            $question_tags = [];

            foreach ($request->tags as $tagName) {

                $searchTag = Tag::where('name', $tagName)->first();

                if (!$searchTag) {
                    $tag = Tag::create([
                        'name' => $tagName
                    ]);
                    array_push($question_tags, [
                        'question_id' => $question->id,
                        'tag_id' => $tag->id,
                    ]);
                } else {
                    array_push($question_tags, [
                        'question_id' => $question->id,
                        'tag_id' => $searchTag->id,
                    ]);
                }
            }
            if (count($question_tags) > 0) {
                QuestionTag::insert($question_tags);
            }

            DB::commit();
            return response($question, 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response($th, 500);
        }
    }
    public function getLastFive()
    {
        return Question::latest()->take(5)->get();
    }
    public function getSingle(Request $request)
    {
        $question =  Question::with('user', 'tags')->find($request->id);
        $answers = Answer::where('question_id', $request->id)->paginate($request->itemPerPage);

        return response()->json([
            'question' => $question,
            'answers' => $answers,
        ]);
    }
    public function get(Request $request)
    {
        return Question::orderBy($request->orderBy, $request->ordering)->with('tags', 'category', 'user')->paginate($request->itemPerPage);
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'question' => 'required|min:6',
            'categoryId' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            Question::where('id', $request->id)->update([
                'question' => $request->question,
                'description' => $request->description,
                'category_id' => $request->categoryId,
            ]);

            QuestionTag::where('question_id', $request->id)->delete();

            $question_tags = [];

            foreach ($request->tags as $tagName) {

                $searchTag = Tag::where('name', $tagName)->first();

                if (!$searchTag) {
                    $tag = Tag::create([
                        'name' => $tagName
                    ]);
                    array_push($question_tags, [
                        'question_id' => $request->id,
                        'tag_id' => $tag->id,
                    ]);
                } else {
                    array_push($question_tags, [
                        'question_id' => $request->id,
                        'tag_id' => $searchTag->id,
                    ]);
                }
            }
            if (count($question_tags) > 0) {
                QuestionTag::insert($question_tags);
            }

            DB::commit();

            return response()->json([
                'message' => 'Question edited successfully!',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response($th, 500);
        }
    }
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        return Question::where('id', $request->id)->delete();
    }
    public function getMyQuestions(Request $request)
    {
        return Question::where('user_id', Auth::user()->id)->orderBy($request->orderBy,  $request->ordering)->paginate($request->itemPerPage);
    }
}
