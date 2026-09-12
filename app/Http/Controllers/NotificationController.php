<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $notifications = collect([

        ]);
        return view('notifications.index', compact('notifications'));
    }
}
