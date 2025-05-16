<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservations = Reservation::with('products')->get();
            return response()->json(['success' => true, 'data' => $reservations], 200);
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
            'date_reservation' => 'required|date',
            'status' => 'required|string',
            'commentaire' => 'nullable|string',
            'colis_size' => 'nullable|string',
            'type_menagement' => 'nullable|string',
            'type_vehicule' => 'nullable|string',
            'distance' => 'nullable|string',
            'from' => 'required|string',
            'to' => 'required|string',
            'heure_reservation' => 'nullable|string',
            'etage' => 'nullable|integer',
            'products' => 'nullable|array',
            'products.*.name' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $reservation = Reservation::create($request->except('products'));
            $client = Client::find($request->client_id);
            $client->points += 5;
            $client->save();
            if ($request->has('products')) {
                foreach ($request->products as $productData) {
                    $reservation->products()->create([
                        'name' => $productData['name'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $reservation->load('products')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $reservation = Reservation::with('products')->find($id);
            if (!$reservation) {
                return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $reservation], 200);
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
            'date_reservation' => 'date',
            'status' => 'string',
            'commentaire' => 'nullable|string',
            'type_menagement' => 'string',
            'type_vehicule' => 'string',
            'distance' => 'numeric',
            'from' => 'string',
            'to' => 'string',
            'heure_reservation' => 'string',
            'etage' => 'integer',
            'products' => 'array',
            'products.*.name' => 'required_with:products|string',
        ];

        $data = $request->all();
        $validationRules = array_intersect_key($rules, $data);
        $validator = Validator::make($data, $validationRules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $reservation = Reservation::find($id);
            if (!$reservation) {
                return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
            }

            $reservation->update($request->except('products'));

            if ($request->has('products')) {
                // Delete existing products
                $reservation->products()->delete();

                // Create new products
                foreach ($request->products as $productData) {
                    $reservation->products()->create([
                        'name' => $productData['name'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $reservation->load('products')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $reservation = Reservation::find($id);
            if (!$reservation) {
                return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
            }
            $reservation->delete();
            return response()->json(['success' => true, 'message' => 'Reservation deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get reservations by transporteur ID.
     */
    public function getReservationsByTransporteur($transporteur_id)
    {
        try {
            $reservations = Reservation::where('transporteur_id', $transporteur_id)->with('client')->get();
            
            if (!$reservations->isEmpty()) {
                foreach ($reservations as $reservation) {
                    if ($reservation->service_id == 2) {
                        $reservations = Reservation::where('transporteur_id', $transporteur_id)
                            ->with('client', 'products')
                            ->get();
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $reservations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reservations by client ID.
     */
    public function getReservationsByClient($client_id)
    {
        try {
            $reservations = Reservation::where('client_id', $client_id)->with('transporteur')->get();
            if (!$reservations->isEmpty()) {
                foreach ($reservations as $reservation) {
                    if ($reservation->service_id == 2) {
                        $reservations = Reservation::where('client_id', $client_id)
                            ->with('transporteur', 'products')
                            ->get();
                        break;
                    }
                }
            }
            return response()->json([
                'success' => true,
                'data' => $reservations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
