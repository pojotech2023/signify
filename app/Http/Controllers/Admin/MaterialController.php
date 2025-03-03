<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Shade;

class MaterialController extends Controller
{
    public function index(){
        $materials = Material::all();
        return view('admin.material_list', compact('materials'));
    }

    public function delete($id){
        $material = Material::find($id);
        $material->shades()->delete();
        $material->delete();
        return back()->with('success', 'Material and Shade deleted successfully!');
    }
}
