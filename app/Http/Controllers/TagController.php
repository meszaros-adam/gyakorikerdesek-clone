<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\UserTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
        ]);

        return Tag::create([
            'name' => $request->name,
        ]);
    }
    public function get(Request $request)
    {
        return Tag::orderBy($request->orderBy, $request->ordering)->withCount('questions')->paginate($request->itemPerPage);
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'id' => 'required|numeric',
        ]);

        return Tag::where('id', $request->id)->update([
            'name' => $request->name
        ]);
    }
    public function getAll()
    {
        return Tag::orderBy('name', 'asc')->get();
    }
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        //it deletes QuestionTags becouse of cascade
        return Tag::where('id', $request->id)->delete();
    }
    public function getPopular()
    {
        //return top 10 tags order by question count 
        return Tag::withCount('questions')->orderBy('questions_count', 'desc')->take(10)->get();
    }
    public function addToWatchlist(Request $request)
    {
        $this->validate($request, [
            'tag_id' => 'required|numeric'
        ]);

        return UserTag::create([
            'user_id' => Auth::user()->id,
            'tag_id' => $request->tag_id,
        ]);
    }
    public function removeFromWatchlist(Request $request)
    {
        $this->validate($request, [
            'tag_id' => 'required|numeric'
        ]);

        return UserTag::where([
            ['tag_id', '=',  $request->tag_id],
            ['user_id', '=', Auth::user()->id],
        ])->delete();
    }
    public function getWatched(Request $request)
    {
        return User::where('users.id', Auth::user()->id)
            ->join('user_tags', 'users.id', '=', 'user_tags.user_id')
            ->join('tags', 'tags.id', '=', 'user_tags.tag_id')
            ->selectRaw(
                'tags.*'
            )
            ->orderBy($request->orderBy,  $request->ordering)
            ->paginate($request->itemPerPage);
    }
    public function deleteWatched(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        return UserTag::where([
            ['tag_id', $request->id],
            ['user_id', Auth::user()->id],
        ])->delete();
    }
}
