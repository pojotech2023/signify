<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'internal_user_id'      => 'required|exists:internal_users,id',
            'date'                  => 'required',
            'check_in_time'         => 'required_if:type,Check In',
            'check_in_location'     => 'required_if:type,Check In|string',
            'check_out_time'        => 'required_if:type,Check Out',
            'check_out_location'    => 'required_if:type,Check Out|string',
            'type'                  => 'required|in:Check In,Check Out',
            'lat'                   => 'required',
            'long'                  => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $date = Carbon::createFromFormat('d-m-Y', $request->date);

        if ($request->type === 'Check In') {
            // Create new attendance entry
            $attendance = Attendance::create([
                'internal_user_id'   => $request->internal_user_id,
                'date'               => $date,
                'check_in_time'      => $request->check_in_time,
                'check_in_location'  => $request->check_in_location,
                'type'               => 'Check In',
                'lat'                => $request->lat,
                'long'               => $request->long,
            ]);
        } else {
            // Update existing attendance with check-out details
            $attendance = Attendance::where('internal_user_id', $request->internal_user_id)
                ->whereDate('date', $date)
                ->first();

            if (!$attendance) {
                return response()->json(['message' => 'Check-in record not found to update check-out.'], 404);
            }

            $attendance->update([
                'check_out_time'     => $request->check_out_time,
                'check_out_location' => $request->check_out_location,
                'type'               => 'Check Out',
                'lat'                => $request->lat,
                'long'               => $request->long,
            ]);
        }

        return response()->json([
            'response code' => 200,
            'message' => $request->type === 'Check In' ? 'Check-in added successfully!' : 'Check-out updated successfully!',
            'data' => $attendance
        ]);
    }

    public function show()
    {
        $loginUserId = auth('api')->id();
        $currentDate = Carbon::now()->toDateString();

        $attendance = Attendance::where('internal_user_id', $loginUserId)
            ->where('date', $currentDate)
            ->get();

        return response()->json([
            'response code' => 200,
            'message' => 'Logged User Attendance Details Fetched Successfully',
            'data' => $attendance
        ]);
    }
}
