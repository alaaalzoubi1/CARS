<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Get all reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $reservations = Reservation::all();

        return response()->json([
            'reservations' => $reservations,
        ], 200);
    }

    /**
     * Get pending reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPending()
    {
        $reservations = Reservation::where('status', 'pending')->get();

        return response()->json([
            'reservations' => $reservations,
        ], 200);
    }

    /**
     * Get canceled reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCanceled()
    {
        $reservations = Reservation::where('status', 'canceled')->get();

        return response()->json([
            'reservations' => $reservations,
        ], 200);
    }

    /**
     * Get approved reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApproved()
    {
        $reservations = Reservation::where('status', 'approved')->get();

        return response()->json([
            'reservations' => $reservations,
        ], 200);
    }

    /**
     * Update reservation status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        if (!is_numeric($request->id) || $request->id <= 0) {
            return response()->json([
                'message' => 'Invalid ID format',
            ], 400);
        }
        // Validate the request data
        $validatedData = $request->validate([
            'status' => 'required',
        ]);

        // Find the reservation by ID
        $reservation = Reservation::find($request->id);

        // Check if the reservation exists
        if (!$reservation) {
            return response()->json([
                'message' => 'Reservation not found',
            ], 404);
        }

        // Update the status
        $reservation->status = $validatedData['status'];
        $reservation->save();

        return response()->json([
            'message' => 'Reservation status updated successfully',
            'reservation' => $reservation,
        ], 200);
    }
}
