<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        $restaurant = $this->currentRestaurant();

        $contacts = Contact::with(['user', 'restaurant', 'replies.user', 'replies.restaurant'])
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('restaurants.settings.contact', compact('contacts'));
    }

    public function send(Request $request)
    {
        $restaurant = $this->currentRestaurant();

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'parent_id' => null,
            'title' => null,
            'message' => $validated['message'],
            'attachments' => $this->storeAttachments($request),
            'status' => 'open',
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function show($id)
    {
        $restaurant = $this->currentRestaurant();

        $contact = Contact::with(['user', 'restaurant', 'replies.user', 'replies.restaurant'])
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')
            ->findOrFail($id);

        return view('restaurants.settings.contact', [
            'contacts' => collect([$contact]),
        ]);
    }

    public function reply(Request $request, $id)
    {
        $restaurant = $this->currentRestaurant();

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $parentContact = Contact::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')
            ->firstOrFail();

        Contact::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'parent_id' => $parentContact->id,
            'title' => $parentContact->title,
            'message' => $validated['message'],
            'attachments' => $this->storeAttachments($request),
            'status' => 'open',
        ]);

        $parentContact->update(['status' => 'open']);

        return redirect()->back()->with('success', 'Follow-up message sent successfully!');
    }

    public function destroy($id)
    {
        $restaurant = $this->currentRestaurant();

        $contact = Contact::where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')
            ->with('replies')
            ->findOrFail($id);

        foreach ($contact->replies as $reply) {
            $this->deletePhysicalFiles($reply->attachments);
        }

        $this->deletePhysicalFiles($contact->attachments);

        $contact->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }

    public function resolve($id)
    {
        $restaurant = $this->currentRestaurant();

        $contact = Contact::where('user_id', Auth::id())
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('parent_id')
            ->findOrFail($id);

        $contact->update(['status' => 'resolved']);

        return redirect()->back()->with('success', 'Message marked as resolved.');
    }

    private function currentRestaurant()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Please login first.');
        }

        $restaurant = Restaurant::where('user_id', $user->id)->first();

        if ($restaurant) {
            return $restaurant;
        }

        // Keep the behavior aligned with the restaurant dashboard for demo/test accounts.
        $fallbackRestaurant = Restaurant::first();

        if (!$fallbackRestaurant) {
            abort(403, 'Restaurant account is not found.');
        }

        return $fallbackRestaurant;
    }

    private function storeAttachments(Request $request): ?array
    {
        $paths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('attachments', 'public');
            }
        }

        return !empty($paths) ? $paths : null;
    }

    private function deletePhysicalFiles($attachmentsData): void
    {
        if (empty($attachmentsData)) {
            return;
        }

        $attachments = is_array($attachmentsData)
            ? $attachmentsData
            : (json_decode($attachmentsData, true) ?: [$attachmentsData]);

        foreach ($attachments as $path) {
            if (!empty($path) && is_string($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
