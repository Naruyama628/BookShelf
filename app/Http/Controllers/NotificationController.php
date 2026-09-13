<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    //

    public function index(Request $request) : View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

public function update(Request $request, string $notificationId): RedirectResponse
{
    $notification = $request->user()
        ->notifications()
        ->findOrFail($notificationId);

    $notification->update([
        'read_at' => now(),
    ]);

    return redirect()->route('notifications.index');
}
}
