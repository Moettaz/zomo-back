<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Http\Requests\UpdateNotificationsRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notifications::all();
        return response()->json([
            'success' => true,
            'data' => $notifications
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not allowed'
        ], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $notification = new Notifications();
            $notification->sender_id = $request->sender_id;
            $notification->receiver_id = $request->receiver_id;
            $notification->service_id = $request->service_id;
            $notification->type = $request->type;
            $notification->message = $request->message;
            $notification->status = $request->status ?? 'pending';
            $notification->date_notification = $request->date_notification ?? now();

            $notification->save();

            // Prepare data for notification
            $data = array();
            $data["notification_id"] = $notification->id;
            $device_token = $request->token;
            // Send notification
            date_default_timezone_set('Africa/Tunis');
            $fcmResponse = Notifications::toSingleDevice(
                $device_token,
                $request->type,
                $request->message,
                null,
                $data,
                $request->type_notification

            );

            return response()->json([
                'success' => true,
                'data' => $notification,
                'fcm_response' => $fcmResponse
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notifications $notifications)
    {
        return response()->json([
            'success' => true,
            'data' => $notifications
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notifications $notifications)
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not allowed'
        ], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationsRequest $request, Notifications $notifications)
    {
        try {
            $notifications->update($request->validated());
            return response()->json([
                'success' => true,
                'data' => $notifications
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notifications $notifications)
    {
        try {
            $notifications->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
