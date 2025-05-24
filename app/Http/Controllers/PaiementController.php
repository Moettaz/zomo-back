<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Http\Requests\StorePaiementRequest;
use App\Http\Requests\UpdatePaiementRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(StorePaiementRequest $request): JsonResponse
    {
        $paiement = Paiement::create($request->validated());
        
        return response()->json([
            'message' => 'Payment created successfully',
            'data' => $paiement
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaiementRequest $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paiement $paiement)
    {
        //
    }

    /**
     * Get all payments for a specific transporter.
     */
    public function getByTransporteurId($transporteurId): JsonResponse
    {
        $paiements = Paiement::where('transporteur_id', $transporteurId)
            ->orderBy('date_paiement', 'desc')
            ->get();

        return response()->json([
            'data' => $paiements
        ]);
    }

    /**
     * Get all payments for a specific client.
     */
    public function getByClientId($clientId): JsonResponse
    {
        $paiements = Paiement::where('client_id', $clientId)
            ->orderBy('date_paiement', 'desc')
            ->get();

        return response()->json([
            'data' => $paiements
        ]);
    }
}
