<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Core\Booking;
use App\Models\Core\ChatMessage;
use App\Models\Core\SavedPlace;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // === Live Chat API Endpoints ===

    public function fetchMessages(Booking $booking)
    {
        $messages = ChatMessage::query()
            ->where('booking_id', $booking->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('H:i')
                ];
            });

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function storeMessage(Request $request, Booking $booking)
    {
        $request->validate([
            'sender_type' => 'required|in:customer,driver',
            'message' => 'required|string|max:1000'
        ]);

        $msg = ChatMessage::create([
            'booking_id' => $booking->id,
            'sender_type' => $request->sender_type,
            'message' => trim($request->message)
        ]);

        return response()->json([
            'success' => true, 
            'message' => [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'message' => $msg->message,
                'time' => $msg->created_at->format('H:i')
            ]
        ]);
    }

    // === Saved Places Endpoints ===

    public function storeSavedPlace(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        SavedPlace::create($request->all());

        return back()->with('success', '✨ Place saved successfully!');
    }

    public function destroySavedPlace(SavedPlace $place)
    {
        $place->delete();
        return back()->with('success', '🗑️ Saved place removed.');
    }
}
