<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Получен список бронирований',
            'count' => $bookings->count(),
            'items' => BookingResource::collection($bookings)
        ]);
    }

    public function show(Booking $booking): JsonResponse
    {
        $this->authorize('view', $booking);

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

    public function destroy(Booking $booking): JsonResponse
    {
        $this->authorize('delete', $booking);

        $this->bookingService->destroy($booking);

        return response()->json(null, 204);
    }

    public function cancel(Booking $booking): JsonResponse
    {
        $this->authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'success' => true,
            'message' => 'Бронь отменена',
            'data' => BookingResource::make($booking)
        ]);
    }
}
