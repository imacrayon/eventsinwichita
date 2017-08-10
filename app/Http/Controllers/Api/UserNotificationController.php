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
        $notifications = !$request->has('read')
            ? auth()->user()->unreadNotifications()
            : auth()->user()->notifications();

        return response()->json(
            $notifications->paginate($request->input('limit', 20))
        );
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
