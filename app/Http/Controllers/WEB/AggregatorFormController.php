<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AggregatorForm;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;


class AggregatorFormController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('aggregator_form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'material_id' => 'required',
            'shade_id' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'unit' => 'required|string',
            'location' => 'required',
            'quantity' => 'required|integer',
            'design_service_need' => 'required',
            'email_id' => 'required|email',
            'site_image' => 'required|array', 
            'site_image.*' => 'file|mimes:jpg,jpeg,png,webp', 
            'design_attachment' => 'required|array',
            'design_attachment.*' => 'file|mimes:jpg,jpeg,png,webp',
            'reference_image' => 'required|array',
            'reference_image.*' => 'file|mimes:jpg,jpeg,png,webp',
            
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
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
    AggregatorForm::create([
        'category_id' => $request->category_id,
        'sub_category_id' => $request->sub_category_id,
        'material_id' => $request->material_id,
        'shade_id' => $request->shade_id,
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
    ]);

    return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}
