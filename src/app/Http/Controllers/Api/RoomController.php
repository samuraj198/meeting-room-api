<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
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

    public function show(int $id): JsonResponse
    {
        $room = $this->roomService->getById($id);

        return response()->json([
            'success' => true,
            'message' => 'Получена комната по id',
            'data' => RoomResource::make($room)
        ]);
    }

    public function store(StoreRoomRequest $request): JsonResponse
    {
        $room = $this->roomService->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Комната успешно создана',
            'data' => RoomResource::make($room)
        ], 201);
    }

    public function update(int $id, UpdateRoomRequest $request): JsonResponse
    {
        $updatedRoom = $this->roomService->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Комната успешно обновлена',
            'data' => RoomResource::make($updatedRoom)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->roomService->destroy($id);

        return response()->json(null, 204);
    }
}
