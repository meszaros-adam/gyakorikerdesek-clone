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
        return Category::orderBy($request->orderBy, $request->ordering)->paginate($request->itemPerPage);
    }
}