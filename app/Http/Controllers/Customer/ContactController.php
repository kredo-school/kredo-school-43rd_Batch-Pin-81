<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->with(['user', 'restaurant', 'replies.user', 'replies.restaurant'])
            ->latest()
            ->get();

        return view('customer.contact', compact('contacts'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
            'parent_id' => ['nullable', 'exists:contacts,id'],
            'attachments.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $parentContact = null;

        if ($request->filled('parent_id')) {
            $parentContact = Contact::where('id', $validated['parent_id'])
                ->where('user_id', Auth::id())
                ->whereNull('parent_id')
                ->firstOrFail();
        }

        Contact::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $parentContact?->restaurant_id,
            'parent_id' => $parentContact?->id,
            'title' => $parentContact?->title,
            'message' => $validated['message'],
            'attachments' => $this->storeAttachments($request),
            'status' => 'open',
        ]);

        if ($parentContact) {
            $parentContact->update(['status' => 'open']);
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function search()
    {
        return view('contact.index');
    }

    public function destroy($id)
    {
        $contact = Contact::where('user_id', Auth::id())
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
