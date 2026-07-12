<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\ContactReplyNotification;


class ContactController extends Controller
{
    public function index(Request $request)
    {
        $currentTab = $request->query('tab', 'all');
        $currentStatus = $request->query('status', 'all');

        $baseQuery = Contact::whereNull('parent_id');

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'customer' => (clone $baseQuery)->whereNull('restaurant_id')->count(),
            'restaurant' => (clone $baseQuery)->whereNotNull('restaurant_id')->count(),
            'open' => (clone $baseQuery)->where('status', 'open')->count(),
            'replied' => (clone $baseQuery)->where('status', 'replied')->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
        ];

        $contactsQuery = Contact::with(['user', 'restaurant', 'replies.user', 'replies.restaurant'])
            ->whereNull('parent_id');

        if ($currentTab === 'customer') {
            $contactsQuery->whereNull('restaurant_id');
        }

        if ($currentTab === 'restaurant') {
            $contactsQuery->whereNotNull('restaurant_id');
        }

        if (in_array($currentStatus, ['open', 'replied', 'resolved'], true)) {
            $contactsQuery->where('status', $currentStatus);
        }

        $contacts = $contactsQuery->latest()->get();

        $selectedContact = null;

        if ($request->filled('contact')) {
            $selectedContact = Contact::with(['user', 'restaurant', 'replies.user', 'replies.restaurant'])
                ->whereNull('parent_id')
                ->find($request->query('contact'));
        }

        return view('admin.contacts.index', compact(
            'contacts',
            'selectedContact',
            'currentTab',
            'currentStatus',
            'counts'
        ));
    }

    public function reply(Request $request, Contact $contact)
    {
        if ($contact->parent_id !== null) {
            abort(404);
        }

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $contact->restaurant_id,
            'parent_id' => $contact->id,
            'title' => $contact->title,
            'message' => $validated['message'],
            'attachments' => $this->storeAttachments($request),
            'status' => 'replied',
        ]);

        $contact->update(['status' => 'replied']);

        // Notify the customer who created the original contact
        $contact->user->notify(
            new ContactReplyNotification($contact, $validated['message'])
        );

        return redirect()
            ->route('admin.contacts.index', ['contact' => $contact->id])
            ->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        if ($contact->parent_id !== null) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:open,replied,resolved'],
        ]);

        $contact->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.contacts.index', ['contact' => $contact->id])
            ->with('success', 'Status updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->parent_id !== null) {
            abort(404);
        }

        $contact->load('replies');

        foreach ($contact->replies as $reply) {
            $this->deletePhysicalFiles($reply->attachments);
        }

        $this->deletePhysicalFiles($contact->attachments);

        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
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
