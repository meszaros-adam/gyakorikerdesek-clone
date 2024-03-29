<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Category;
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

            $question->load('category', 'tags', 'user');

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
        } catch (Throwable $th) {
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
        $question =  Question::with('user', 'tags', 'category')->find($request->id);
        $answers = Answer::where('question_id', $request->id)->paginate($request->itemPerPage);

        return response()->json([
            'question' => $question,
            'answers' => $answers,
        ]);
    }
    public function get(Request $request)
    {
        return Question::select(['questions.*', 'categories.name as category_name', 'users.nickname as user_nickname'])
            ->with('tags', 'category', 'user')
            ->join('categories', 'questions.category_id', '=', 'categories.id')
            ->join('users', 'questions.user_id', '=', 'users.id')
            ->orderBy($request->orderBy, $request->ordering)
            ->paginate($request->itemPerPage);
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

            $this->deleteUnusedTags();

            DB::commit();

            return response()->json([
                'message' => 'Question edited successfully!',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response($th, 500);
        }
    }
    public function deleteUnusedTags()
    {
        Tag::doesntHave('questions')->delete();
    }
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);


        try {
            DB::beginTransaction();
            Question::where('id', $request->id)->delete();
            $this->deleteUnusedTags();
            DB::commit();
            return response('Question deleted sucessfully!', 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response($th, 500);
        }
    }
    public function getMyQuestions(Request $request)
    {
        return Question::where('questions.user_id', Auth::user()->id)
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function getMyAnsweredQuestions(Request $request)
    {
        return Question::whereHas('answers', function ($q) {
            $q->where('user_id', Auth::user()->id);
        })
            ->join('answers', 'answers.question_id', '=', 'questions.id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function getByCategory(Request $request)
    {
        return Question::whereHas('category', function ($q) use ($request) {
            return $q->where('id', $request->category_id);
        })
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function getByTag(Request $request)
    {
        return Question::whereHas('tags', function ($q) use ($request) {
            return $q->where('tag_id', $request->tag_id);
        })
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function getAll(Request $request)
    {
        return Question::leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function search(Request $request)
    {
        $keyword =  $request->keyword;
        return Question::when($keyword != '', function ($q) use ($keyword) {
            $q->where('question', 'LIKE', "%{$keyword}%")
                ->orWhereHas('tags', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
        })
            ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function getByWatchedTags(Request $request)
    {
        $watchedTags = array_map(function ($watchedTag) {
            return $watchedTag['tag_id'];
        }, Auth::user()->watchedTags->toArray());

        return Question::whereHas('tags', function ($q) use ($watchedTags) {
            $q->whereIn('tags.id', $watchedTags);
        })
            ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
            ->selectRaw('questions.*, Max(answers.created_at) AS latest_answer_at')
            ->groupBy('id')
            ->with('category', 'tags')
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);;
    }
}
