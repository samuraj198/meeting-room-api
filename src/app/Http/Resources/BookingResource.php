<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timezone = $request->header('X-Timezone', 'UTC');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'room' => RoomResource::make($this->room),
            'start_time' => $this->start_time->setTimezone($timezone)
                ->toDateTimeString(),
            'end_time' => $this->end_time->setTimezone($timezone)
                ->toDateTimeString(),
            'status' => $this->status,
            'purpose' => $this->purpose,
            'created_at' => $this->created_at->setTimezone($timezone)
                ->toDateTimeString(),
        ];
    }
}
