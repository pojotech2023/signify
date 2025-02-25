<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AggregatorForm;

class AggregatorFormController extends Controller
{
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
