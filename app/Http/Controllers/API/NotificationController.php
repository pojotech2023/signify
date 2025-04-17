<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    // protected $firebaseService;

    // public function __construct(FirebaseService $firebaseService)
    // {
    //     $this->firebaseService = $firebaseService;
    // }

    public function send(Request $request, FirebaseService $firebase)
    {
        $request->validate([
            'token' => 'required|string',
            'title' => 'required|string',
            'body'  => 'required|string'

        ]);
        $token = $request->input('token');
        $title = $request->input('title', 'Default Title');
        $body = $request->input('body', 'Default Body');

        $firebase->sendNotification($token, $title, $body);

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
