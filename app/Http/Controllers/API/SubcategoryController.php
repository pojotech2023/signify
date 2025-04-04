<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubCategory;
use App\Models\Category;

class SubcategoryController extends Controller
{

    public function index()
    {
        $subcategory = SubCategory::with('category')->orderBy('id', 'desc')->get();
        return response()->json([
            'response code' => 200,
            'message' => 'Subcategory Fetched Successfully!.',
            'data' => $subcategory
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'category_id' => 'required|exists:categories,id',
                'sub_category' => 'required'
            ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $subcategory = SubCategory::create([
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Subcategory Added Successfully!.',
            'data' => $subcategory
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category' => 'required|unique:sub_categories,sub_category'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $subcategory = SubCategory::findOrFail($id);

        $subcategory = $subcategory->update([
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Subcategory Updated Successfully!.',
            'data' => $subcategory
        ]);
    }

    public function delete($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();
        return response()->json([
            'response code' => 200,
            'message' => 'Subcategory Deleted Successfully!.',
            'data' => $subcategory
        ]);
    }
}
