<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Shade;
use App\Models\ShadeImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class MaterialController extends Controller
{
    public function index()
    {
        $material = Material::orderBy('id', 'desc')->get();

        return response()->json(['response code' => 200, 'data' => $material]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required',
            'material_name' => 'required',
            'main_img' => 'required|image|mimes:jpg,jpeg,png,webp',
            'material_sub_img' => 'required|array',
            'material_sub_img.*' => 'required|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:5120',
            'shades' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
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

        $shades = json_decode($request->shades, true);
        //Store Shades (from JSON)
        if (!empty($shades)) {
            foreach ($shades as $shade) {

                $shadeRecord = Shade::create([
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'material_id' => $material->id,
                    'shade_name' => $shade['shade_name'],
                ]);

                if (isset($shade['shade_img']) && is_array($shade['shade_img'])) {
                    foreach ($shade['shade_img'] as $key => $file) {
                        ShadeImage::create([
                            'shade_id' => $shadeRecord->id,
                            'shade_img' => $file,
                        ]);
                    }
                }
            }
        }
        return response()->json([
            'response code' => 200,
            'message' => 'Material & Shades Added Successfully',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'material_name' => 'required',
            'main_img' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'material_sub_img' => 'nullable|array',
            'material_sub_img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:5120',
            'shades' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
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

        if ($request->has('material_sub_img')) {
            $uploadedFiles = $request->file('material_sub_img');

            foreach ($uploadedFiles as $file) {
                foreach ($subImages as $key => $value) {
                    if (empty($value)) {
                        $subImages[$key] = $file->store('materials/sub-img', 'public');
                        break;
                    }
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

        $shades = json_decode($request->shades, true);

        if (!empty($shades)) {
            foreach ($shades as $shade) {
                $shadeId = $shade['shade_id'] ?? null;

                if ($shadeId) {
                    //UPDATE EXISTING SHADE
                    $shadeRecord = Shade::find($shadeId);
                    if ($shadeRecord) {
                        // Update shade name only if provided
                        $shadeRecord->shade_name = $shade['shade_name'] ?? $shadeRecord->shade_name;
                        $shadeRecord->save();

                        // Only update images if provided and there is an empty column
                        if (!empty($shade['shade_img'])) {
                            foreach ($shade['shade_img'] as $file) {
                                ShadeImage::create([
                                    'shade_id' => $shadeRecord->id,
                                    'shade_img' => $file,
                                ]);
                            }
                        }

                        $shadeRecord->save();
                    }
                } else {
                    // CREATE NEW SHADE
                    $shadeRecord = Shade::create([
                        'material_id' => $id,
                        'shade_name' => $shade['shade_name'] ?? null,
                        'category_id' => $request->category_id ?? null,
                        'sub_category_id' => $request->sub_category_id ?? null,
                    ]);
        
                    if (!empty($shade['shade_img'])) {
                        foreach ($shade['shade_img'] as $file) {
                            ShadeImage::create([
                                'shade_id' => $shadeRecord->id,
                                'shade_img' => $file,
                            ]);
                        }
                    }
                }
            }
        }


        return response()->json(['response code' => 200, 'message' => 'Material & Shades data updated successfully']);
    }

    public function delete($id)
    {
        $material = Material::find($id);
        $material->shades()->delete();
        $material->delete();
        return response()->json(['response code' => 200, 'message' => 'Material & Shades data deleted successfully']);
    }

    //Material single sub image delete
    public function deleteSubImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:materials,id',
            'image_path' => 'required|string',
        ]);

        $material = Material::findOrFail($request->id);

        $subImageFields = ['sub_img1', 'sub_img2', 'sub_img3', 'sub_img4'];
        $deleted = false;

        foreach ($subImageFields as $field) {
            if ($material->{$field} === $request->image_path) {
                if (Storage::disk('public')->exists($request->image_path)) {
                    Storage::disk('public')->delete($request->image_path);
                }
                $material->update([$field => null]);
                $deleted = true;
                break;
            }
        }
        if ($deleted) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Material SubImage deleted successfully'
            ]);
        } else {
            return response()->json([
                'response_code' => 404,
                'message' => 'Image path not found in material'
            ]);
        }
    }

    //Shade image delete
    public function deleteImage(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'shade_id' => 'required|exists:shades,id',
            'shade_img' => 'required|exists:shade_images,shade_img',
        ]);

        $shadeImg = ShadeImage::where('shade_id', $request->shade_id)
            ->where('shade_img', $request->shade_img)
            ->first();

        if (Storage::disk('public')->exists($shadeImg->shade_img)) {
            Storage::disk('public')->delete($shadeImg->shade_img);
        }
        $shadeImg->delete();

        return response()->json([
            'response_code' => 200,
            'message' => 'Shades Image deleted successfully'
        ]);
    }
}
