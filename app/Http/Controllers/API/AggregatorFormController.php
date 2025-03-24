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

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json([
            'respone code' => 200,
            'data' => $categories
        ], 200);
    }

    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json([
            'respone code' => 200,
            'data' => $subcategories
        ], 200);
    }

    public function getMaterials($subcategory_id)
    {
        $materials = Material::where('sub_category_id', $subcategory_id)->get();
        return response()->json([
            'respone code' => 200,
            'data' => $materials
        ], 200);
        // return response()->json($materials);
    }

    public function getShades($material_id)
    {

        $shades = Shade::where('material_id', $material_id)->get();
        return response()->json([
            'respone code' => 200,
            'data' => $shades
        ], 200);
        // return response()->json($shades);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'material_id' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'unit' => 'required|string',
            'location' => 'required',
            'quantity' => 'required|integer',
            'design_service_need' => 'required',
            'email_id' => 'required|email|unique:aggregator_forms,email_id',
            'site_image' => 'required|array',
            'site_image.*' => 'file|mimes:jpg,jpeg,png,webp',
            'design_attachment' => 'required|array',
            'design_attachment.*' => 'file|mimes:jpg,jpeg,png,webp',
            'reference_image' => 'required|array',
            'reference_image.*' => 'file|mimes:jpg,jpeg,png,webp',
            'shades' => 'required|string',
            'mobile_no' => 'required|string|max:10',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

       
        $siteImages = [];
        if ($request->hasFile('site_image')) {
            foreach ($request->file('site_image') as $file) {
                $path = $file->store('uploads/site_images', 'public');
                $siteImages[] = $path;
            }
        }

        $designAttachments = [];
        if ($request->hasFile('design_attachment')) {
            foreach ($request->file('design_attachment') as $file) {
                $path = $file->store('uploads/design_attachments', 'public');
                $designAttachments[] = $path;
            }
        }

        $referenceImages = [];
        if ($request->hasFile('reference_image')) {
            foreach ($request->file('reference_image') as $file) {
                $path = $file->store('uploads/reference_images', 'public');
                $referenceImages[] = $path;
            }
        }


        $aggregatorForm = AggregatorForm::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'material_id' => $request->material_id,
            'width' => $request->width,
            'height' => $request->height,
            'unit' => $request->unit,
            'location' => $request->location,
            'quantity' => $request->quantity,
            'design_service_need' => $request->design_service_need,
            'email_id' => $request->email_id,
            'site_image' => implode(',', $siteImages),  // Convert array to comma-separated string
            'design_attachment' => implode(',', $designAttachments),
            'reference_image' => implode(',', $referenceImages),
            'mobile_no' => $request->mobile_no
        ]);


        // Decode Shades JSON
        $shades = json_decode($request->shades, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON in shades'], 422);
        }

        // Save Shades with Selected Image
        foreach ($shades as $shade) {
            if (isset($shade['shade_id']) && isset($shade['selected_img'])) {
                $aggregatorForm->shade()->create([
                    'shade_id' => $shade['shade_id'],
                    'selected_img' => $shade['selected_img'],
                ]);
            }
        }

        return response()->json([
            'response code' => 200,
            'message' => 'Aggregator Form Submitted Successfully'
        ], 200);
    }
}
