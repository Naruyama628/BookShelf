<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /**
     * 通知一覧画面表示
     *
     * @return View 通知一覧画面
     */
    public function index() : View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * 通知既読処理
     *
     * @param Request $request 
     * @param string $id 既読にする通知
     * @return RedirectResponse 通知一覧画面
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->update([
            'read_at' => now(),
        ]);

        return back()->with('success', '通知を既読にしました。');
    }
}
