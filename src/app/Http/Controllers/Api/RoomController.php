<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Policies\RoomPolicy;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct(private RoomService $roomService)
    {}

    public function index(): JsonResponse
    {
        $rooms = $this->roomService->getActiveRooms();

        return response()->json([
            'success' => true,
            'message' => 'Список активных комнат',
            'count' => $rooms->count(),
            'items' => RoomResource::collection($rooms)
        ]);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Получена комната по id',
            'data' => RoomResource::make($room)
        ]);
    }

    public function store(StoreRoomRequest $request): JsonResponse
    {
        $this->authorize('create', Room::class);

        $room = $this->roomService->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Комната успешно создана',
            'data' => RoomResource::make($room)
        ], 201);
    }

    public function update(Room $room, UpdateRoomRequest $request): JsonResponse
    {
        $this->authorize('update', $room);

        $updatedRoom = $this->roomService->update($room, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Комната успешно обновлена',
            'data' => RoomResource::make($updatedRoom)
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $this->authorize('delete', $room);

        $this->roomService->destroy($room);

        return response()->json(null, 204);
    }
}
