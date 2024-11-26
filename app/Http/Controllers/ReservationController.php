<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Get all reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $reservations = Reservation::with(['user:id,name', 'car'])->get();

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
        $reservations = Reservation::with(['user:id,name', 'car'])->where('status', 'pending')->get();

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
        $reservations = Reservation::with(['user:id,name', 'car'])->where('status', 'canceled')->get();

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
        $reservations = Reservation::with(['user:id,name', 'car'])->where('status', 'approved')->get();

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
    public function store(Request $request)
    { // Validate the request data
         $validatedData = $request->validate([
             'car_id' => 'required|exists:cars,id',
             'with_driver' => 'required|boolean',
             'start' => 'required|date|after_or_equal:today',
             'end' => 'required|date|after:start',
             ]);
         // Create a new reservation
        $user_id = Auth::user()->getAuthIdentifier();
         $reservation = Reservation::create([
             'user_id' => $user_id,
             'car_id' => $validatedData['car_id'],
             'with_driver' => $validatedData['with_driver'],
             'start' => $validatedData['start'],
             'end' => $validatedData['end'],
             'status' => 'pending',
         ]);
         return response()->json([
             'message' => 'Reservation created successfully',
             'reservation' => $reservation, ],
             201);
    }
    /**
     * Display a paginated list of reservations for the authenticated user.
     *
     * * @return \Illuminate\Http\JsonResponse
     */
    public function myReservations()
    {
         $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['car' => function($query)
            {
                $query->with(['images' => function($query)
                {
                    $query->where('is_main', true);
                }]);
            }])
            ->paginate(5);
         return response()->json([
             'reservations' => $reservations
         ]);
    }
}
