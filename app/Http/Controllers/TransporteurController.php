<?php

namespace App\Http\Controllers;

use App\Models\Transporteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TransporteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transporteurs = Transporteur::all();
            return response()->json(['success' => true, 'data' => $transporteurs], 200);
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:transporteurs,email',
            'username' => 'required|regex:/^[^0-9]+$/',
            'password' => 'required|min:8',
            'phone' => 'required|digits:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('transporteurs', 'public');
                $data['image_url'] = 'storage/' . $imagePath;
            }

            $transporteur = Transporteur::create($data);
            return response()->json(['success' => true, 'data' => $transporteur], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $transporteur = Transporteur::find($id);
            if (!$transporteur) {
                return response()->json(['success' => false, 'message' => 'Transporteur not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $transporteur], 200);
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
            'email' => 'email|unique:transporteurs,email,' . $id,
            'username' => 'regex:/^[^0-9]+$/',
            'password' => 'nullable|min:8',
            'phone' => 'digits:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

        $data = $request->all();
        $validationRules = array_intersect_key($rules, $data);
        $validator = Validator::make($data, $validationRules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $transporteur = Transporteur::find($id);
            if (!$transporteur) {
                return response()->json(['success' => false, 'message' => 'Transporteur not found'], 404);
            }

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('transporteurs', 'public');
                $data['image_url'] = 'storage/' . $imagePath;
            }

            $transporteur->update($data);
            return response()->json(['success' => true, 'data' => $transporteur], 200);
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
            $transporteur = Transporteur::find($id);
            if (!$transporteur) {
                return response()->json(['success' => false, 'message' => 'Transporteur not found'], 404);
            }
            $transporteur->delete();
            return response()->json(['success' => true, 'message' => 'Transporteur deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
