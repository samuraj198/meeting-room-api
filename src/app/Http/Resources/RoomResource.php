<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'description' => $this->description ?? null,
            'is_active' => $this->is_active ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
