<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $query = Notification::query()->where('is_read', false);
        
        if ($request->has('user_id') && $request->has('user_type')) {
            $query->where('user_id', $request->user_id)
                  ->where('user_type', $request->user_type);
        }
        
        $query->update(['is_read' => true]);
        
        return back()->with('success', 'All notifications marked as read.');
    }
}
