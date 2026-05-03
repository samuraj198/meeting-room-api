<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ModelNotFoundException;
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
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'У пользователя нет доступа'
            ], 403);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Получена бронь',
            'data' => BookingResource::make($booking)
        ]);
    }

    public function userBookings(): JsonResponse
    {
        $bookings = $this->bookingService->getUserBookings(auth()->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Получены брони зарегистрированного пользователя',
            'count' => $bookings->count(),
            'items' => BookingResource::collection($bookings)
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->store($request->validated(), auth()->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно забронировали комнату',
            'data' => BookingResource::make($booking)
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->bookingService->destroy($id);

        return response()->json(null, 204);
    }

    public function cancel(int $id): JsonResponse
    {
        $booking = $this->bookingService->cancel($id);

        return response()->json([
            'success' => true,
            'message' => 'Бронь отменена',
            'data' => BookingResource::make($booking)
        ]);
    }
}
