<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('id', 'desc')->get();
        return response()->json([
            'response code' => 200,
            'message' => 'Category Feteched Successfully!.',
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'category' => 'required|unique:categories,category'
            ],
            [
                'category.required' => 'Category name is required.',
                'category.unique' => 'This category already exists.'
            ]
        );

        if($validate->fails())
        {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $category = Category::create([
            'category' => $request->category
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Category Added Successfully!.',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'category' => 'required'
        ]);

        if($validate->fails())
        {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $category = Category::findOrFail($id);

        $category = $category->update(['category' => $request->category]);

        return response()->json([
            'response code' => 200,
            'message' => 'Category Updated Successfully!.',
            'data' => $category
        ]);
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'response code' => 200,
            'message' => 'Category Deleted Successfully!.',
            'data' => $category
        ]);
    }
}
