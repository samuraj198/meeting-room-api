<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAvailableRoomRequest;
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

    public function index(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $rooms = $this->roomService->getActiveRooms($page);

        return response()->json([
            'success' => true,
            'message' => 'Список активных комнат',
            'count' => $rooms->count(),
            'items' => RoomResource::collection($rooms)
        ]);
    }

    public function getAvailableRooms(GetAvailableRoomRequest $request): JsonResponse
    {
        $rooms = $this->roomService->getAvailableRooms($request->date,
                                                        $request->start_time,
                                                        $request->end_time);

        return response()->json([
            'success' => true,
            'message' => 'Список свободных комнат',
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
