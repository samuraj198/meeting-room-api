<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {}

    public function index(): JsonResponse
    {
        $bookings = $this->bookingService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Получен список бронирований',
            'count' => $bookings->count(),
            'items' => BookingResource::collection($bookings)
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $booking = $this->bookingService->getById($id);

        if ($booking === null) {
            return response()->json([
                'success' => false,
                'message' => 'Бронь не найдена'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Получена бронь',
            'data' => BookingResource::make($booking)
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->store($request->validated(), User::first()->id);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно забронировали комнату',
            'data' => BookingResource::make($booking)
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $check = $this->bookingService->destroy($id);

        if ($check === null) {
            return response()->json([
                'success' => false,
                'message' => 'Бронь не найдена'
            ], 404);
        }

        return response()->json(null, 204);
    }
}
