<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateReclamationRequest;

class ReclamationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reclamations = Reclamation::with(['client', 'transporteur', 'service'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $reclamations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'This endpoint is not available for API usage'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $reclamation = Reclamation::create([
                'client_id' => $request->client_id,
                'transporteur_id' => $request->transporteur_id,
                'service_id' => $request->service_id,
                'date_creation' => now(),
                'sujet' => $request->sujet,
                'description' => $request->description,
                'status' => $request->status ?? 'pending',
                'priorite' => $request->priorite ?? 'medium'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reclamation created successfully',
                'data' => $reclamation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create reclamation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reclamation $reclamation)
    {
        $reclamation->load(['client', 'transporteur', 'service']);
        return response()->json([
            'status' => 'success',
            'data' => $reclamation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reclamation $reclamation)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'This endpoint is not available for API usage'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reclamation $reclamation)
    {
        try {
            $reclamation->update([
                'client_id' => $request->client_id ?? $reclamation->client_id,
                'transporteur_id' => $request->transporteur_id ?? $reclamation->transporteur_id,
                'service_id' => $request->service_id ?? $reclamation->service_id,
                'sujet' => $request->sujet ?? $reclamation->sujet,
                'description' => $request->description ?? $reclamation->description,
                'status' => $request->status ?? $reclamation->status,
                'priorite' => $request->priorite ?? $reclamation->priorite
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reclamation updated successfully',
                'data' => $reclamation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update reclamation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reclamation $reclamation)
    {
        try {
            $reclamation->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Reclamation deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete reclamation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reclamations by client ID
     */
    public function getByClientId($client_id)
    {
        try {
            $reclamations = Reclamation::with(['client', 'transporteur', 'service'])
                ->where('client_id', $client_id)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $reclamations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch reclamations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reclamations by transporteur ID
     */
    public function getByTransporteurId($transporteur_id)
    {
        try {
            $reclamations = Reclamation::with(['client', 'transporteur', 'service'])
                ->where('transporteur_id', $transporteur_id)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $reclamations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch reclamations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
