<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Fetch all categories
        return view('admin.category_list', compact('categories'));
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'category' => 'required|unique:categories,category',
        ],
        [ 
        'category.required' => 'Category name is required.',
        'category.unique' => 'This category already exists.'
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        Category::create(['category' => $request->category]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request) {
        $validate = Validator::make($request->all(),[
            'category_id' => 'required|exists:categories,id',
            'category' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
    
        $category = Category::findOrFail($request->category_id);
        $category->update(['category' => $request->category]);
    
        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function delete($id){
        $category = Category::find($id);
        $category->delete();
        return back()->with('success', 'Category deleted successfully !');
    }
}
