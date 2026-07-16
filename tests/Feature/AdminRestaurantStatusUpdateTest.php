<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRestaurantStatusUpdateTest extends TestCase
{
  use RefreshDatabase;

  public function test_admin_approve_status_promotes_owner_and_creates_notification(): void
  {
    $admin = User::factory()->create([
      'role_id' => User::ROLE_ADMIN,
    ]);

    $restaurantOwner = User::factory()->create([
      'role_id' => User::ROLE_USER,
    ]);

    $restaurant = Restaurant::create([
      'user_id' => $restaurantOwner->id,
      'restaurant_name' => 'Test Restaurant',
      'description' => 'Test description',
      'postal_code' => '123-4567',
      'prefecture' => 'Tokyo',
      'city' => 'Shibuya',
      'street_address_building' => '1-2-3',
      'phone_number' => '0312345678',
      'status' => Restaurant::STATUS_PENDING,
    ]);

    $this->actingAs($admin)
      ->patch(route('admin.restaurants.status', $restaurant), [
        'status' => Restaurant::STATUS_APPROVED,
      ])
      ->assertRedirect();

    $this->assertDatabaseHas('restaurants', [
      'id' => $restaurant->id,
      'status' => Restaurant::STATUS_APPROVED,
    ]);

    $this->assertDatabaseHas('users', [
      'id' => $restaurantOwner->id,
      'role_id' => User::ROLE_RESTAURANT,
    ]);

    $notification = $restaurantOwner->fresh()->notifications()->latest()->first();

    $this->assertNotNull($notification);
    $this->assertSame('restaurant_application', $notification->data['type']);
    $this->assertSame('approved', $notification->data['status']);
    $this->assertSame(
      "Your restaurant 'Test Restaurant' has been approved.",
      $notification->data['message']
    );
  }
}
