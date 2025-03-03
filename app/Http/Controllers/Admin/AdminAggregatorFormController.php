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
    public function index()
    {
        $categories = Category::all();
        return view('admin.aggregator', compact('categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); 
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'material_name' => 'required',
            'material_main_img' => 'required|image|mimes:jpg,jpeg,png,webp',
            'material_sub_img' => 'required|array',
            'material_sub_img.*' => 'required|image|mimes:jpg,jpeg,png,webp',
            'shade_name' => 'required|array',
            'shade_name.*' => 'required|string',
            'shade_img' => 'required|array',
            'shade_img.*' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($validate->fails()) {
            // dd($validate->errors()->all());
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $main_img = $request->file('material_main_img')->store('materials/main-img', 'public');
        
        $material_sub_img = [];
        if ($request->hasFile('material_sub_img')) {
            foreach ($request->file('material_sub_img') as $file) {
                $path = $file->store('materials/sub_img', 'public');
                $material_sub_img[] = $path;
            }
        }
    
        $material = Material::create([
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'material_name' => $request->material_name,
            'material_main_img' => $main_img,
            'material_sub_img' => implode(',', $material_sub_img)
        ]);

        $material_id = $material->id;

        foreach ($request->shade_name as $key  => $shadeName) {
            $shadeImgPath = $request->file('shade_img')[$key]->store('shades', 'public');

            Shade::create([
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'material_id' => $material_id,
                'shade_name' => $shadeName,
                'shade_img' => $shadeImgPath
            ]);
        }
        return redirect()->back()->with('success', 'Aggregator data saved successfully.');
    }
}
