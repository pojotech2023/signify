<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Material;
use App\Models\Shade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAggregatorFormController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $material = Material::find($id);
            if (!$material) {
                abort(404, 'Material not found');
            }
        } else {
            $material = null;
        }

        $categories = Category::all();
        return view('admin.aggregator', compact('categories', 'material'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); 
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required',
            'material_name' => 'required',
            'main_img' => 'required|image|mimes:jpg,jpeg,png,webp',
            'material_sub_img' => 'required|array',
            'material_sub_img.*' => 'required|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:5120',
            'shade_name' => 'required|array',
            'shade_name.*' => 'required|string',
            'shade_img' => 'required|array',
            'shade_img.*' => 'required|array',
            'shade_img.*.*' => 'required|image|mimes:jpg,jpeg,png,webp',

        ]);

        if ($validate->fails()) {
            //dd($validate->errors()->all());
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Upload main imag
        $main_img = null;
        if ($request->hasFile('main_img')) {
            $main_img = $request->file('main_img')->store('materials/main-img', 'public');
        }

        // Upload sub images (only up to 4 images allowed)
        $sub_images = array_fill(0, 4, null); // Initialize array with 4 null values
        if ($request->hasFile('material_sub_img')) {
            foreach ($request->file('material_sub_img') as $key => $file) {
                if ($key < 4) { // Limit to 4 images
                    $sub_images[$key] = $file->store('materials/sub_img', 'public');
                }
            }
        }

        // Upload video
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('materials/video', 'public');
        }
        // create material
        $material = Material::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'material_name' => $request->material_name,
            'main_img' => $main_img,
            'sub_img1' => $sub_images[0] ?? null,
            'sub_img2' => $sub_images[1] ?? null,
            'sub_img3' => $sub_images[2] ?? null,
            'sub_img4' => $sub_images[3] ?? null,
            'video' => $videoPath,
        ]);

        // Store Shades
        $material_id = $material->id;
        if ($request->has('shade_name')) {
            foreach ($request->shade_name as $key => $shadeName) {
                $shadeImages = array_fill(0, 4, null); // Initialize with 4 null values
        
                // Check if this shade has associated images
                if (isset($request->shade_img[$key]) && is_array($request->shade_img[$key])) {
                    foreach ($request->shade_img[$key] as $imgKey => $file) {
                        if ($imgKey < 4) { // Store up to 4 images
                            $shadeImages[$imgKey] = $file->store('shades', 'public');
                        }
                    }
                }
        
                Shade::create([
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'material_id' => $material_id,
                    'shade_name' => $shadeName,
                    'shade_img1' => $shadeImages[0] ?? null,
                    'shade_img2' => $shadeImages[1] ?? null,
                    'shade_img3' => $shadeImages[2] ?? null,
                    'shade_img4' => $shadeImages[3] ?? null,
                ]);
            }
        }
        
        // if ($request->hasFile('shade_img')) {
        //     foreach ($request->shade_name as $key => $shadeName) {
        //         $shadeImgPath = $request->file('shade_img')[$key]->store('shades', 'public');

        //         Shade::create([
        //             'category_id' => $request->category_id,
        //             'sub_category_id' => $request->sub_category_id,
        //             'material_id' => $material_id,
        //             'shade_name' => $shadeName,
        //             'shade_img' => $shadeImgPath
        //         ]);
        //     }
        // }

        return redirect()->back()->with('success', 'Aggregator data saved successfully.');
    }
}
