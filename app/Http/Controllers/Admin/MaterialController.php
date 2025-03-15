<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Shade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('id', 'desc')->get();
        return view('admin.aggregator_list', compact('materials'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all()); 
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'material_name' => 'required',
            'main_img' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'material_sub_img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'shade_name' => 'required|array',
            'shade_name.*' => 'required|string',
            'shade_img.*.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:5120'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $material = Material::findOrFail($id);

        // **MAIN IMAGE HANDLING**
        $main_img = $material->main_img;
        if ($request->hasFile('main_img')) {
            if (!empty($material->main_img)) {
                Storage::disk('public')->delete($material->main_img);
            }
            $main_img = $request->file('main_img')->store('materials/main-img', 'public');
        }
        // **Video HANDLING**
        $video = $material->video;
        if ($request->hasFile('video')) {
            if (!empty($material->video)) {
                Storage::disk('public')->delete($material->video);
            }
            $video = $request->file('video')->store('materials/video', 'public');
        }
        // **SUB IMAGES HANDLING (Individual Columns)**
        $subImages = [
            $material->sub_img1,
            $material->sub_img2,
            $material->sub_img3,
            $material->sub_img4
        ]; // Get existing images

        // **Loop through uploaded images and store in empty slots**
        foreach ($request->file('material_sub_img', []) as $file) {
            foreach ($subImages as $key => $value) {
                if (empty($value)) { // Find first empty slot
                    $subImages[$key] = $file->store('materials/sub-img', 'public');
                    break; // Store one image per empty column, then move to next file
                }
            }
        }

        // **Update Data Array**
        $material->update([
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'material_name'   => $request->material_name,
            'main_img'        => $main_img,
            'video'           => $video,
            'sub_img1'        => $subImages[0] ?? null,
            'sub_img2'        => $subImages[1] ?? null,
            'sub_img3'        => $subImages[2] ?? null,
            'sub_img4'        => $subImages[3] ?? null,
        ]);

        // **SHADE IMAGES HANDLING**
        foreach ($request->shade_name as $shadeKey => $shadeName) {
            $shadeId = $request->shade_id[$shadeKey] ?? null;

            if ($shadeId) {
                // **UPDATE Existing Shade**
                $shade = Shade::find($shadeId);
                if ($shade) {
                    $shade->shade_name = $shadeName;
                    $shade->category_id = $request->category_id;
                    $shade->sub_category_id = $request->sub_category_id;

                    // Handle existing shade images in an array
                    $shadeImages = [
                        $shade->shade_img1,
                        $shade->shade_img2,
                        $shade->shade_img3,
                        $shade->shade_img4
                    ];

                    // Handle **nested array** for shade images
                    if (isset($request->shade_img[$shadeKey])) {
                        foreach ($request->shade_img[$shadeKey] as $file) {
                            foreach ($shadeImages as $key => $value) {
                                if (empty($value)) {
                                    $shadeImages[$key] = $file->store('shades', 'public');
                                    break;
                                }
                            }
                        }
                    }

                    // Update shade images
                    $shade->update([
                        'shade_img1' => $shadeImages[0] ?? null,
                        'shade_img2' => $shadeImages[1] ?? null,
                        'shade_img3' => $shadeImages[2] ?? null,
                        'shade_img4' => $shadeImages[3] ?? null
                    ]);
                }
            } else {
                // **CREATE New Shade**
                $shade = new Shade();
                $shade->material_id = $id;
                $shade->shade_name = $shadeName;
                $shade->category_id = $request->category_id;
                $shade->sub_category_id = $request->sub_category_id;

                // Create new shade image array
                $shadeImages = [null, null, null, null];

                if (isset($request->shade_img[$shadeKey])) {
                    foreach ($request->shade_img[$shadeKey] as $file) {
                        foreach ($shadeImages as $key => $value) {
                            if (empty($value)) {
                                $shadeImages[$key] = $file->store('shades', 'public');
                                break;
                            }
                        }
                    }
                }

                // Save new shade with images
                $shade->shade_img1 = $shadeImages[0] ?? null;
                $shade->shade_img2 = $shadeImages[1] ?? null;
                $shade->shade_img3 = $shadeImages[2] ?? null;
                $shade->shade_img4 = $shadeImages[3] ?? null;
                $shade->save();
            }
        }


        return redirect()->back()->with('success', 'Material updated successfully');
    }

    //Material single sub image delete
    public function deleteMaterialsubImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:materials,id',
            'field' => 'required|in:sub_img1,sub_img2,sub_img3,sub_img4',
        ]);

        $material = Material::findOrFail($request->id);
        $imagePath = $material->{$request->field};
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
        $material->update([$request->field => null]);

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }

    public function deleteShadeImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:shades,id',
            'field' => 'required|in:shade_img1,shade_img2,shade_img3,shade_img4',
        ]);

        $shade = Shade::findOrFail($request->id);
        $imagePath = $shade->{$request->field};
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
        $shade->update([$request->field => null]);

        return redirect()->back()->with('success', 'Shade image deleted successfully!');
    }

    public function delete($id)
    {
        $material = Material::find($id);
        $material->shades()->delete();
        $material->delete();
        return back()->with('success', 'Material and Shade deleted successfully!');
    }
}
