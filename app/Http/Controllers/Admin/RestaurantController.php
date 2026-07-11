<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Notifications\RestaurantApplicationStatus;
use Illuminate\Support\Facades\Auth;



class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function pending()
    {
        $restaurants = Restaurant::where('status', 'pending')->get();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function approve(Request $request, Restaurant $restaurant)
    {
        $restaurant->update([
            'status' => Restaurant::STATUS_APPROVED,
        ]);

        // Change the user's role to Restaurant Owner
        $restaurant->user->update([
            'role_id' => 2,
        ]);

        // Notify restaurant owner
        $restaurant->user->notify(
            new RestaurantApplicationStatus(
                'approved',
                $restaurant
            )
        );

        // Mark admin notification as read
        auth()
            ->user()
            ->notifications()
            ->find($request->notification_id)
            ?->markAsRead();

        return back()->with('success', 'Restaurant approved.');
    }

    public function reject(Request $request, Restaurant $restaurant)
    {
        $restaurant->update([
            'status' => Restaurant::STATUS_REJECTED,
        ]);

        // Notify restaurant owner
        $restaurant->user->notify(
            new RestaurantApplicationStatus(
                'rejected',
                $restaurant
            )
        );

        auth()
            ->user()
            ->notifications()
            ->find($request->notification_id)
            ?->markAsRead();

        return back()->with('success', 'Restaurant rejected.');
    }

    // private function markNotificationAsRead(?string $notificationId): void
    // {
    //     if (!$notificationId) {
    //         return;
    //     }

    //     $notification = auth()
    //         ->user()
    //         ->notifications()
    //         ->find($notificationId);

    //     if ($notification) {
    //         $notification->markAsRead();
    //     }
    // }

    // public function active()
    // {
    //     $restaurants = Restaurant::where('status', 'active')->get();

    //     return view('admin.restaurants.index', compact('restaurants'));
    // }

    public function suspended()
    {
        $restaurants = Restaurant::where('status', 'suspended')->get();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function updateStatus(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $restaurant->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Restaurant status updated successfully.'
        );
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load('user');

        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        $restaurant->load('user');

        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',

            'website' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',

            'capacity' => 'nullable|integer',
        ]);

        // ----------------------------
        // CLEAN OPERATING HOURS
        // ----------------------------
        $hours = $request->hours ?? [];

        $cleaned = [];

        foreach ($hours as $day => $data) {

            $opens = $data['open'] ?? [];
            $closes = $data['close'] ?? [];

            foreach ($opens as $i => $open) {

                $close = $closes[$i] ?? null;

                if (!$open || !$close) {
                    continue;
                }

                $cleaned[$day][] = [
                    'open' => $open,
                    'close' => $close,
                ];
            }
        }

        // ----------------------------
        // UPDATE RESTAURANT
        // ----------------------------
        $restaurant->update([
            'restaurant_name' => $request->restaurant_name,
            'description' => $request->description,
            'address' => $request->address,
            'phone_number' => $request->phone_number,

            'website' => $request->website,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,

            'capacity' => $request->capacity,

            'operating_hours' => $cleaned,
        ]);

        return redirect()
            ->route('admin.restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated successfully.');
    }


    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return back()->with(
            'success',
            'Restaurant deleted successfully.'
        );
    }
}
