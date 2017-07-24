<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserNotificationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Fetch all unread notifications for the user.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($request->has('limit')) {
            return response()->json(
                auth()->user()->unreadNotifications()
                    ->limit($request->limit)
                    ->get()
            );
        }

        return response()->json(auth()->user()->unreadNotifications);
    }
    /**
     * Mark a specific notification as read.
     *
     * @param \App\User $user
     * @param int       $notificationId
     */
    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();

        return response()->json([], 204);
    }
}
