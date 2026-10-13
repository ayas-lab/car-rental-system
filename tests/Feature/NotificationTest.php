<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationConfirmed;
use App\Notifications\ReservationCreated;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase; // Reset DB between each test

    // ─────────────────────────────────────────────
    // ReservationCreated
    // ─────────────────────────────────────────────

    /**
     * Seed roles before every test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_client_and_admins_receive_notification_when_reservation_created(): void
    {
        Notification::fake();

        // Arrange: create a client, an admin, and a vehicle
        $client = User::factory()->create();
        $client->assignRole('client');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $vehicle = Vehicle::factory()->create([
            'status'     => 'available',
            'daily_rate' => 100,
        ]);

        // Act: log in as client and book the vehicle
        $response = $this->actingAs($client)->post('/client/reservations', [
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'end_date'   => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertRedirect();

        // Assert: both client and admin were notified
        Notification::assertSentTo($client, ReservationCreated::class);
        Notification::assertSentTo($admin, ReservationCreated::class);
    }

    public function test_no_notification_sent_when_dates_overlap(): void
    {
        Notification::fake();

        $client = User::factory()->create();
        $client->assignRole('client');

        $vehicle = Vehicle::factory()->create(['status' => 'available']);

        // Create an existing reservation that blocks the dates
        Reservation::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->addDays(2),
            'end_date'   => now()->addDays(6),
            'status'     => 'confirmed',
        ]);

        // Try to book overlapping dates
        $this->actingAs($client)->post('/client/reservations', [
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->addDays(3)->format('Y-m-d'),
            'end_date'   => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Assert: no notification was sent
        Notification::assertNothingSent();
    }

    // ─────────────────────────────────────────────
    // ReservationConfirmed
    // ─────────────────────────────────────────────

    public function test_client_is_notified_when_admin_confirms_reservation(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $client = User::factory()->create();
        $client->assignRole('client');

        $reservation = Reservation::factory()->create([
            'user_id' => $client->id,
            'status'  => 'pending',
        ]);

        // Act: admin confirms the reservation
        $this->actingAs($admin)->patch(
            route('admin.reservations.status', $reservation),
            ['status' => 'confirmed']
        );

        // Assert
        Notification::assertSentTo($client, ReservationConfirmed::class);
    }

    // ─────────────────────────────────────────────
    // ReservationCancelled
    // ─────────────────────────────────────────────

    public function test_client_is_notified_when_they_cancel_their_reservation(): void
    {
        Notification::fake();

        $client = User::factory()->create();
        $client->assignRole('client');

        $reservation = Reservation::factory()->create([
            'user_id' => $client->id,
            'status'  => 'confirmed',
        ]);

        // Act: client cancels their reservation
        $this->actingAs($client)->delete(
            route('client.reservations.cancel', $reservation)
        );

        // Assert
        Notification::assertSentTo($client, ReservationCancelled::class);
    }
}