<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();
    
        // Check if search input is present
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
    
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }
    
        $vendors = $query->orderBy('id', 'desc')->get();
    
        return view('admin.vendor.vendor_list', compact('vendors'));
    }
    

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'district'  => 'required|string',
            'location'  => 'required|string'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $vendor = Vendor::create([
            'name'      => $request->name,
            'mobile_no' => $request->mobile_no,
            'district'  => $request->district,
            'location'  => $request->location
        ]);

        return redirect()->back()->with('success', 'Vendor added successfully!');
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'name'      => 'required|string|max:255',
            'mobile_no' => 'required|numeric|digits:10',
            'district'  => 'required|string',
            'location'  => 'required|string'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $vendor = Vendor::findOrFail($request->vendor_id);

        $vendor->update([
            'name'      => $request->name,
            'mobile_no' => $request->mobile_no,
            'district'  => $request->district,
            'location'  => $request->location
        ]);

        return redirect()->back()->with('success', 'Vendor updated successfully!');
    }

    public function delete($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return back()->with('success', 'Vendor Deleted Successfully!');
    }

    public function search(Request $request)
    {   
        $vendors = Vendor::where('name', 'LIKE', $request->name . '%')
            ->select('name', 'mobile_no')
            ->get();

        return response()->json($vendors);
    }

    //Attendance List 
    public function attendanceList()
    {
        $attendances = Attendance::with('internalUser')->orderBy('id', 'desc')->get();
        return view('admin.attendance.attendance_list', compact('attendances'));
    }
}
