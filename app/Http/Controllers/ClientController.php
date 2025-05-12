<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $clients = Client::all();
            return response()->json(['success' => true, 'data' => $clients], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['success' => false, 'message' => 'Not implemented'], 501);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'Client not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return response()->json(['success' => false, 'message' => 'Not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'email' => 'email|unique:clients,email,' . $id,
            'username' => 'regex:/^[^0-9]+$/',
            'password' => 'nullable|min:8',
            'phone' => 'digits:8',
            'points' => 'integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

        $data = $request->all();
        $validationRules = array_intersect_key($rules, $data);
        $validator = Validator::make($data, $validationRules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'Client not found'], 404);
            }

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('clients', 'public');
                $data['image_url'] = 'storage/' . $imagePath;
            }

            $client->update($data);
            return response()->json(['success' => true, 'data' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::find($id);
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'Client not found'], 404);
            }
            $client->delete();
            return response()->json(['success' => true, 'message' => 'Client deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Authenticate a user and return a token.
     */
}
