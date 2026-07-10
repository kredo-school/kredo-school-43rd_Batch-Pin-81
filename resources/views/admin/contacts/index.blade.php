@extends('layouts.admin')

@section('title', 'Contact Management')

@section('content')
    <style>
        .contact-tab {
            color: #0a2540;
            border: 1px solid transparent;
        }

        .contact-tab.active {
            background-color: #ffffff;
            border-color: #c8d0d8;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
            font-weight: 700;
        }

        .contact-list-item {
            color: #0a2540;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .contact-list-item.active,
        .contact-list-item:hover {
            background-color: #f1f5f9;
            border-color: #0a2540;
        }

        .message-bubble {
            max-width: 78%;
            border-radius: 14px;
            padding: 1rem;
        }

        .message-admin {
            align-self: flex-end;
            background-color: #0a2540;
            color: #ffffff;
        }

        .message-user {
            align-self: flex-start;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            color: #0a2540;
        }
    </style>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #0a2540;">Contact Management</h2>
                <p class="text-muted mb-0">Manage inquiries from customers and restaurants.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <div class="bg-white rounded-4 shadow-sm p-3">
                    <div class="p-2 rounded-3 mb-3" style="background-color: #dbe1e8;">
                        <div class="nav nav-pills gap-1">
                            <a class="nav-link contact-tab px-3 py-2 {{ $currentTab === 'all' ? 'active' : '' }}"
                                href="{{ route('admin.contacts.index', ['tab' => 'all', 'status' => $currentStatus]) }}">
                                All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
                            </a>
                            <a class="nav-link contact-tab px-3 py-2 {{ $currentTab === 'customer' ? 'active' : '' }}"
                                href="{{ route('admin.contacts.index', ['tab' => 'customer', 'status' => $currentStatus]) }}">
                                Customer <span class="badge bg-secondary ms-1">{{ $counts['customer'] }}</span>
                            </a>
                            <a class="nav-link contact-tab px-3 py-2 {{ $currentTab === 'restaurant' ? 'active' : '' }}"
                                href="{{ route('admin.contacts.index', ['tab' => 'restaurant', 'status' => $currentStatus]) }}">
                                Restaurant <span class="badge bg-secondary ms-1">{{ $counts['restaurant'] }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <a class="btn btn-sm {{ $currentStatus === 'all' ? 'btn-dark' : 'btn-outline-dark' }}"
                            href="{{ route('admin.contacts.index', ['tab' => $currentTab, 'status' => 'all']) }}">All</a>
                        <a class="btn btn-sm {{ $currentStatus === 'open' ? 'btn-dark' : 'btn-outline-dark' }}"
                            href="{{ route('admin.contacts.index', ['tab' => $currentTab, 'status' => 'open']) }}">Open
                            {{ $counts['open'] }}</a>
                        <a class="btn btn-sm {{ $currentStatus === 'replied' ? 'btn-dark' : 'btn-outline-dark' }}"
                            href="{{ route('admin.contacts.index', ['tab' => $currentTab, 'status' => 'replied']) }}">Replied
                            {{ $counts['replied'] }}</a>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @forelse($contacts as $contact)
                            <a class="contact-list-item text-decoration-none p-3 {{ $selectedContact?->id === $contact->id ? 'active' : '' }}"
                                href="{{ route('admin.contacts.index', ['tab' => $currentTab, 'status' => $currentStatus, 'contact' => $contact->id]) }}">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="fw-bold text-truncate">
                                        {{ $contact->title ?: Str::limit($contact->message, 36) }}
                                    </div>
                                    <span
                                        class="badge {{ $contact->status === 'open' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $contact->status }}
                                    </span>
                                </div>
                                <div class="small text-muted mt-1">
                                    {{ $contact->inquiry_type }} /
                                    {{ $contact->user?->full_name ?: $contact->user?->email }}
                                </div>
                                <div class="small text-muted mt-1">
                                    {{ $contact->created_at->format('Y-m-d H:i') }}
                                    @if ($contact->replies->count() > 0)
                                        ・{{ $contact->replies->count() }} replies
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-5">
                                No contacts found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="bg-white rounded-4 shadow-sm p-4 min-vh-75">
                    @if (!$selectedContact)
                        <div class="text-center text-muted py-5">
                            <i class="fa-regular fa-message fs-1 mb-3"></i>
                            <h5 class="fw-bold">Select a message to reply.</h5>
                            <p class="mb-0">Choose a contact from the list on the left.</p>
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4">
                            <div>
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <span class="badge bg-primary">{{ $selectedContact->inquiry_type }}</span>
                                    <span
                                        class="badge {{ $selectedContact->status === 'open' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $selectedContact->status }}
                                    </span>
                                </div>
                                <h4 class="fw-bold mb-1" style="color: #0a2540;">
                                    {{ $selectedContact->title ?: 'Inquiry #' . $selectedContact->id }}
                                </h4>
                                <div class="text-muted small">
                                    From: {{ $selectedContact->user?->full_name ?: $selectedContact->user?->email }}
                                    @if ($selectedContact->restaurant)
                                        / {{ $selectedContact->restaurant->restaurant_name }}
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.contacts.status', $selectedContact) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status"
                                        value="{{ $selectedContact->status === 'open' ? 'replied' : 'open' }}">
                                    <button type="submit" class="btn btn-outline-dark btn-sm">
                                        Mark as {{ $selectedContact->status === 'open' ? 'replied' : 'open' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.contacts.destroy', $selectedContact) }}" method="POST"
                                    onsubmit="return confirm('Delete this contact thread?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3 mb-4" style="max-height: 520px; overflow-y: auto;">
                            @php
                                $messages = collect([$selectedContact])->merge($selectedContact->replies);
                            @endphp

                            @foreach ($messages as $message)
                                @php
                                    $isAdmin = $message->user?->isAdmin();
                                    $attachments = is_array($message->attachments)
                                        ? $message->attachments
                                        : (json_decode($message->attachments ?? '[]', true) ?:
                                        []);
                                @endphp

                                <div class="message-bubble {{ $isAdmin ? 'message-admin' : 'message-user' }}">
                                    <div class="small fw-bold mb-2">
                                        {{ $message->sender_label }}
                                        <span
                                            class="fw-normal opacity-75 ms-2">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                    <div style="white-space: pre-wrap;">{{ $message->message }}</div>

                                    @if (!empty($attachments))
                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            @foreach ($attachments as $path)
                                                @if (!empty($path) && is_string($path))
                                                    <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $path) }}" class="img-thumbnail"
                                                            style="max-width: 140px; max-height: 140px; object-fit: cover;"
                                                            alt="Attachment">
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('admin.contacts.reply', $selectedContact) }}" method="POST"
                            enctype="multipart/form-data" class="border-top pt-4">
                            @csrf
                            <label for="admin-reply-message" class="form-label fw-bold">Reply</label>
                            <textarea id="admin-reply-message" name="message" class="form-control mb-3" rows="4"
                                placeholder="Type your reply..." required>{{ old('message') }}</textarea>

                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <input type="file" name="attachments[]" class="form-control" multiple accept="image/*">
                                <button type="submit" class="btn btn-dark px-4">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Send Reply
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
