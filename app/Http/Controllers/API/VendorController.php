<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Check if search input is present
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile_no', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $vendors = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Vendors Fetched Successfully!.',
            'data' => $vendors
        ]);
    }
    // public function index()
    // {
    //     $vendors = Vendor::orderBy('id', 'desc')->get();

    //     return response()->json([
    //         'response code' => 200,
    //         'message' => 'Vendors Fetched Successfully!.',
    //         'data' => $vendors
    //     ]);
    // }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'district'  => 'required|string',
            'location'  => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $vendor = Vendor::create([
            'name'      => $request->name,
            'mobile_no' => $request->mobile_no,
            'district'  => $request->district,
            'location'  => $request->location
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Vendor Added Successfully!.',
            'data' => $vendor
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'district'  => 'required|string',
            'location'  => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $vendor = Vendor::findOrFail($id);

        $vendor = $vendor->update([
            'name'      => $request->name,
            'mobile_no' => $request->mobile_no,
            'district'  => $request->district,
            'location'  => $request->location
        ]);

        return response()->json([
            'response code' => 200,
            'message' => 'Vendor Updated Successfully!.',
            'data' => $vendor
        ]);
    }

    public function delete($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return response()->json([
            'response code' => 200,
            'message' => 'Vendor Deleted Successfully!.',
            'data' => $vendor
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $vendor = Vendor::where('name', 'LIKE', $request->name . '%')
            ->select('name', 'mobile_no')
            ->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Vendor Search Fetched Successfully!.',
            'data' => $vendor
        ]);
    }
}
