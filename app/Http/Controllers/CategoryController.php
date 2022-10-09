<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function add(Request $request){
        $this->validate($request,[
            "name" => 'required|min:3'
        ]);

        return Category::create([
            'name' => $request->name,
        ]);
    }
    public function get(Request $request){
        return Category::orderBy($request->orderBy, $request->ordering)->withCount('questions')->paginate($request->itemPerPage);
    }
    public function getAll(){
        return Category::orderBy('name', 'asc')->get();
    }
    public function edit(Request $request){
        $this->validate($request,[
            "name" => 'required|min:3',
            "id" => 'required|numeric',
        ]);

        return Category::where('id', $request->id)->update([
           "name" => $request->name,
        ]);
    }
    public function delete(Request $request){
        $this->validate($request,[
            "id" => "required|numeric"
        ]);

        //it deletes Questions becouse of cascade
        return Category::where('id', $request->id)->delete();
    }
}
