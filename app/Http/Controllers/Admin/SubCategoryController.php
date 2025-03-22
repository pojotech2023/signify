<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('category')->orderBy('id', 'desc')->get();
        $categories = Category::all();
        return view('admin.subcategory_list', compact('subcategories', 'categories'));
    }
    
    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category' => 'required'
        ]);
        
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        Subcategory::create([
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category
        ]);
        return redirect()->back()->with('success', 'Subcategory added successfully');
    }

    public function update(Request $request) {
        $validate = Validator::make($request->all(),[
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'sub_category' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
    
        $subcategory = Subcategory::findOrFail($request->subcategory_id);
        $subcategory->update([
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category
        ]);
    
        return redirect()->back()->with('success', 'Subcategory updated successfully.');
    }

    public function delete($id){
        $category = SubCategory::find($id);
        $category->delete();
        return back()->with('success', 'Subcategory deleted successfully !');
    }

}
