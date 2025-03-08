<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AggregatorForm;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Material;
use App\Models\Shade;

class AggregatorFormController extends Controller
{

    public function getCategories(){
        $categories = Category::all();
        return response()->json('success', $categories);
    } 

    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }

    public function getMaterials($subcategory_id)
    {
        $materials = Material::where('sub_category_id', $subcategory_id)->get();
        return response()->json($materials);
    }

    public function getShades($material_id)
    {

        $shades = Shade::where('material_id', $material_id)->get();
        return response()->json($shades);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category' => 'required',
            'sub_category' => 'required',
            'material_img.*' => 'required|file|mimes:jpg,jpeg,png,webp',
            'material_name.*' => 'required|string',
            'shades_img.*' => 'required|file|mimes:jpg,jpeg,png,webp',
            'shades_name.*' => 'required|string',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'unit' => 'required|string',
            'location' => 'required',
            'quantity' => 'required|integer',
            'design_service_need' => 'required',
            'email_id' => 'required|email',
            'site_image.*' => 'required|file|mimes:jpg,jpeg,png,webp',
            'design_attachment.*' => 'required|file|mimes:jpg,jpeg,png,webp',
            'reference_image.*' => 'required|file|mimes:jpg,jpeg,png,webp'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $validated = $request->all();

        $uploadFiles = function ($files) {
            if (!$files) return null;
            $files = is_array($files) ? $files : [$files];
            return implode(',', array_map(fn($file) => $file->store('uploads', 'public'), $files));
        };
        $validated['material_img'] = $uploadFiles($request->file('material_img'));
        $validated['shades_img'] = $uploadFiles($request->file('shades_img'));
        $validated['site_image'] = $uploadFiles($request->file('site_image'));
        $validated['design_attachment'] = $uploadFiles($request->file('design_attachment'));
        $validated['reference_image'] = $uploadFiles($request->file('reference_image'));

        $validated['material_name'] = implode(',', (array) $request->input('material_name'));
        $validated['shades_name'] = implode(',', (array) $request->input('shades_name'));

        AggregatorForm::create($validated);

        return response()->json(['message' => 'Aggregator Form Submitted Successfully'], 200);
    }
}
