<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_rooms_list()
    {
        $rooms = Room::factory(10)->create();
        $countActiveRooms = $rooms->where('is_active', true)->count();

        $response = $this->get('/api/rooms');

        $response->assertJsonPath('count', $countActiveRooms);
        $response->assertJsonStructure([
            'success',
            'message',
            'count',
            'items' => [
                '*' => [
                    'id',
                    'name',
                    'capacity',
                    'description',
                    'is_active',
                    'created_at',
                    'updated_at'
                ]
            ]
        ])->assertStatus(200);
    }

    public function test_get_single_room()
    {
        $rooms = Room::factory(2)->create();

        $response = $this->get('/api/rooms/' . $rooms[1]->id);

        $response->assertJsonPath('data.id', $rooms[1]->id);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'capacity',
                'description',
                'is_active',
                'created_at',
                'updated_at'
            ]
        ])->assertStatus(200);
    }

    public function test_create_room()
    {
        $data = [
            'name' => 'Test Room',
            'capacity' => 10,
            'description' => 'Test Room',
            'is_active' => true,
        ];

        $response = $this->post('/api/rooms', $data);

        $this->assertDatabaseHas('rooms', $data);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'capacity',
                'description',
                'is_active',
                'created_at',
                'updated_at'
            ]
        ])->assertStatus(201);
    }

    public function test_update_room()
    {
        $room = Room::factory()->create();
        $this->assertDatabaseHas('rooms', [
            'name' => $room->name
        ]);

        $data = [
            'name' => 'Test Room closed',
            'is_active' => false,
        ];

        $response = $this->put('/api/rooms/' . $room->id, $data);

        $this->assertDatabaseHas('rooms', $data);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'capacity',
                'description',
                'is_active',
                'created_at',
                'updated_at'
            ]
        ])->assertStatus(200);
    }

    public function test_delete_room()
    {
        $room = Room::factory()->create();
        $this->assertDatabaseHas('rooms', [
            'name' => $room->name
        ]);

        $response = $this->delete('/api/rooms/' . $room->id);
        $this->assertDatabaseMissing('rooms', [
            'name' => $room->name
        ]);

        $response->assertStatus(204);
    }

    #[DataProvider('invalidRoomDataProvider')]
    public function test_room_validation_fails($invalidData, $expectedField)
    {
        $response = $this->postJson('/api/rooms', $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedField);
    }

    public static function invalidRoomDataProvider(): array
    {
        return [
            'Поле name должно быть заполнено' => [
                ['name' => '', 'capacity' => 1],
                'name'
            ],
            'В поле name должно быть максимум 100 символов' => [
                ['name' => Str::random(105), 'capacity' => 1],
                'name'
            ],
            'Поле capacity должно быть заполнено' => [
                ['name' => 'test'],
                'capacity'
            ],
            'Поле capacity не должно быть отрицательным или равным 0' => [
                ['name' => 'test', 'capacity' => -2],
                'capacity'
            ],
            'Поле capacity не должно быть больше 50' => [
                ['name' => 'test', 'capacity' => 51],
                'capacity'
            ],
            'Поле description должно быть максимум 500 символов' => [
                ['name' => 'test', 'capacity' => 1, 'description' => Str::random(501)],
                'description'
            ]
        ];
    }

    public function test_unique_name_room()
    {
        $room = Room::factory()->create();

        $response = $this->postJson('/api/rooms', [
            'name' => $room->name,
            'capacity' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }
}
