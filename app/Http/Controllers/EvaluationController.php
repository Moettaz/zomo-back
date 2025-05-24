<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Transporteur;
use App\Http\Requests\StoreEvaluationRequest;
use App\Http\Requests\UpdateEvaluationRequest;

class EvaluationController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(StoreEvaluationRequest $request)
    {
        $evaluation = Evaluation::create($request->validated());
        
        // Update transporteur's average note
        $transporteur = Transporteur::find($request->transporteur_id);
        $averageNote = Evaluation::where('transporteur_id', $request->transporteur_id)->avg('note');
        $transporteur->update(['note_moyenne' => $averageNote]);

        return response()->json([
            'message' => 'Evaluation created successfully',
            'data' => $evaluation
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        //
    }

    /**
     * Get evaluations by client ID
     */
    public function getByClientId($clientId)
    {
        $evaluations = Evaluation::where('client_id', $clientId)
            ->with('transporteur')
            ->get();

        return response()->json([
            'data' => $evaluations
        ]);
    }

    /**
     * Get evaluations by transporteur ID
     */
    public function getByTransporteurId($transporteurId)
    {
        $evaluations = Evaluation::where('transporteur_id', $transporteurId)
            ->with('client')
            ->get();

        return response()->json([
            'data' => $evaluations
        ]);
    }
}
