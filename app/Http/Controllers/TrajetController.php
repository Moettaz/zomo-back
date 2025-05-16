<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
class TrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $trajets = Trajet::all();
            return response()->json(['success' => true, 'data' => $trajets], 200);
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
            'client_id' => 'required|exists:clients,id',
            'transporteur_id' => 'required|exists:transporteurs,id',
            'service_id' => 'required',
            'date_heure_depart' => 'required|date',
            'date_heure_arrivee' => 'required|date',
            'point_depart' => 'required|string',
            'point_arrivee' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'etat' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $trajet = Trajet::create($request->all());
            $client = Client::find($request->client_id);
            $client->points += 15;
            $client->save();
            return response()->json(['success' => true, 'data' => $trajet], 201);
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
            $trajet = Trajet::with('transporteur')->with('client')->find($id);
            if (!$trajet) {
                return response()->json(['success' => false, 'message' => 'Trajet not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $trajet], 200);
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
            'client_id' => 'exists:clients,id',
            'transporteur_id' => 'exists:transporteurs,id',
            'service_id' => 'exists:services,id',
            'date_heure_depart' => 'date',
            'date_heure_arrivee' => 'date|after:date_heure_depart',
            'point_depart' => 'string',
            'point_arrivee' => 'string',
            'prix' => 'numeric|min:0',
            'note' => 'nullable|numeric|min:0|max:5',
            'etat' => 'string'
        ];

        $data = $request->all();
        $validationRules = array_intersect_key($rules, $data);
        $validator = Validator::make($data, $validationRules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $trajet = Trajet::find($id);
            if (!$trajet) {
                return response()->json(['success' => false, 'message' => 'Trajet not found'], 404);
            }

            $trajet->update($data);
            return response()->json(['success' => true, 'data' => $trajet], 200);
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
            $trajet = Trajet::find($id);
            if (!$trajet) {
                return response()->json(['success' => false, 'message' => 'Trajet not found'], 404);
            }
            $trajet->delete();
            return response()->json(['success' => true, 'message' => 'Trajet deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get trajets by transporteur ID.
     */
    public function getTrajetsByTransporteur($transporteur_id)
    {
        try {
            $trajets = Trajet::with('client')
                ->where('transporteur_id', $transporteur_id)
                ->get();
            return response()->json([
                'success' => true,
                'data' => $trajets
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trajets by client ID.
     */
    public function getTrajetsByClient($client_id)
    {
        try {
            $trajets = Trajet::with('transporteur')->where('client_id', $client_id)->get();
            return response()->json([
                'success' => true,
                'data' => $trajets
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
