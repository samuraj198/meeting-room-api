<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking()
    {
        $data = [
            'user_id' => User::factory()->create()->id,
            'room_id' => Room::factory()->create()->id,
            'start_time' => '2026-05-08 09:19:35',
            'end_time' => '2026-05-08 09:20:35',
        ];

        $response = $this->post('/api/bookings', $data);

        $this->assertDatabaseHas('bookings', $data);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'user_id',
                'room',
                'start_time',
                'end_time',
                'status',
                'purpose',
                'created_at'
            ]
        ])->assertStatus(201);
    }

    public function test_create_booking_for_booked_room()
    {
        $booking = Booking::factory()->create([
            'start_time' => '2026-05-08 09:00:00',
            'end_time' => '2026-05-08 10:00:00',
        ]);

        $data = [
            'user_id' => User::factory()->create()->id,
            'room_id' => $booking->room_id,
            'start_time' => '2026-05-08 09:30:00',
            'end_time' => '2026-05-08 10:30:00',
        ];

        $response = $this->postJson('/api/bookings', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('room_id');
    }

    public function test_get_list_of_bookings()
    {
        $bookings = Booking::factory(10)->create();

        $response = $this->get('/api/bookings');

        $response->assertJsonStructure([
            'success',
            'message',
            'count',
            'items' => [
                '*' => [
                    'id',
                    'user_id',
                    'room',
                    'start_time',
                    'end_time',
                    'status',
                    'purpose',
                    'created_at'
                ]
            ]
        ])->assertStatus(200);
    }

    public function test_get_booking()
    {
        $bookings = Booking::factory(2)->create();

        $response = $this->get('/api/bookings/' . $bookings[1]->id);

        $response->assertJsonPath('data.id', $bookings[1]->id);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'user_id',
                'room',
                'start_time',
                'end_time',
                'status',
                'purpose',
                'created_at'
            ]
        ])->assertStatus(200);
    }

    public function test_destroy_booking()
    {
        $bookings = Booking::factory(2)->create();
        $this->assertDatabaseCount('bookings', 2);

        $response = $this->delete('/api/bookings/' . $bookings[1]->id);

        $this->assertDatabaseCount('bookings', 1);
        $response->assertStatus(204);
    }

    #[DataProvider('invalidBookingDataProvider')]
    public function test_booking_validation_fails($invalidData, $expectedField)
    {
        $response = $this->postJson('/api/bookings', $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedField);
    }

    public static function invalidBookingDataProvider()
    {
        return [
            'Поле room_id обязательно для заполнения' => [
                ['start_time' => '2026-05-08 09:00:00', 'end_time' => '2026-05-08 10:00:00'],
                'room_id'
            ],
            'Поле start_time обязательно для заполнения' => [
                ['end_time' => '2026-05-08 09:00:00'],
                'start_time'
            ],
            'Поле end_time обязательно для заполнения' => [
                ['start_time' => '2026-05-08 09:00:00'],
                'end_time'
            ],
            'Время в start_time должно быть позже или равно времени в данный момент' => [
                ['start_time' => '2023-05-08 09:00:00', 'end_time' => '2025-05-08 10:00:00'],
                'start_time'
            ],
            'Время в end_time должно быть позже, чем start_time' => [
                ['start_time' => '2025-05-08 09:00:00', 'end_time' => '2025-05-08 08:00:00'],
                'end_time'
            ],
            'В поле purpose должно быть максимум 500 символов' => [
                ['start_time' => '2025-05-08 09:00:00', 'end_time' => '2025-05-08 10:00:00', 'purpose' => Str::random(501)],
                'purpose'
            ]
        ];
    }
}
