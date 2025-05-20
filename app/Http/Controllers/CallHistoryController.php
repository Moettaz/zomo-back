<?php

namespace App\Http\Controllers;

use App\Models\CallHistory;
use App\Models\User;
use App\Models\Client;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CallHistoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'etat' => 'required|in:cancelled,received,sended',
            'duration' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $callHistory = CallHistory::create($request->all());

        return response()->json([
            'message' => 'Call history created successfully',
            'data' => $callHistory
        ], 201);
    }

    public function getById($id)
    {
        $callHistory = CallHistory::where('sender_id', $id)
            ->orWhere('receiver_id', $id)
            ->with(['sender.role', 'receiver.role'])
            ->get()
            ->map(function ($call) {
                // Get specific data for sender
                $senderSpecificData = null;
                if ($call->sender->role->slug === 'client') {
                    $senderSpecificData = Client::where('user_id', $call->sender_id)->first();
                } else if ($call->sender->role->slug === 'transporteur') {
                    $senderSpecificData = Transporteur::where('user_id', $call->sender_id)->first();
                }

                // Get specific data for receiver
                $receiverSpecificData = null;
                if ($call->receiver->role->slug === 'client') {
                    $receiverSpecificData = Client::where('user_id', $call->receiver_id)->first();
                } else if ($call->receiver->role->slug === 'transporteur') {
                    $receiverSpecificData = Transporteur::where('user_id', $call->receiver_id)->first();
                }

                return [
                    'id' => $call->id,
                    'sender' => [
                        'user' => $call->sender,
                        'specific_data' => $senderSpecificData
                    ],
                    'receiver' => [
                        'user' => $call->receiver,
                        'specific_data' => $receiverSpecificData
                    ],
                    'etat' => $call->etat,
                    'duration' => $call->duration,
                    'created_at' => $call->created_at,
                    'updated_at' => $call->updated_at
                ];
            });

        return response()->json([
            'call_history' => $callHistory
        ]);
    }
} 