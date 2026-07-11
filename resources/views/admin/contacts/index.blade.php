@extends('layouts.admin')

@section('content')
    <style>
        .contact-page {
            color: #0a2540;
        }

        .contact-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .open-pill {
            background: #fff3e3;
            color: #f05a00;
            border-radius: 999px;
            padding: .7rem 1.2rem;
            font-weight: 800;
        }

        .contact-tabs .btn {
            border-radius: .65rem;
            padding: .45rem .85rem;
            font-size: .9rem;
            font-weight: 700;
        }

        .contact-tabs .btn-active {
            background: #0a2540;
            border-color: #0a2540;
            color: #fff;
        }

        .contact-card {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 1rem;
            padding: 1.4rem;
        }

        .contact-card.open-card {
            border-color: #ffc27a;
        }

        .avatar-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            flex: 0 0 46px;
        }

        .avatar-customer {
            background: #dcecff;
            color: #2367ff;
        }

        .avatar-restaurant {
            background: #fff0d9;
            color: #f05a00;
        }

        .type-badge {
            border-radius: .55rem;
            padding: .25rem .5rem;
            font-size: .8rem;
            font-weight: 700;
        }

        .badge-customer {
            background: #dcecff;
            color: #2367ff;
        }

        .badge-restaurant {
            background: #fff0d9;
            color: #bf4a00;
        }

        .status-badge {
            border-radius: 999px;
            padding: .35rem .75rem;
            font-weight: 700;
            font-size: .82rem;
        }

        .status-open {
            background: #fff0d9;
            color: #c84b00;
        }

        .status-replied {
            background: #dff6e9;
            color: #14743b;
        }

        .status-resolved {
            background: #e9ecef;
            color: #495057;
        }

        .reply-box {
            background: #f8fafc;
            border-left: 4px solid #0a2540;
            border-radius: .75rem;
            padding: 1rem;
        }

        .thread-box {
            background: #f8fafc;
            border-radius: .75rem;
            padding: .9rem 1rem;
        }

        .btn-navy {
            background: #0a2540;
            color: #fff;
            border: 1px solid #0a2540;
        }

        .btn-navy:hover {
            background: #143554;
            color: #fff;
            border-color: #143554;
        }

        .status-select {
            max-width: 190px;
            border-radius: .65rem;
            font-weight: 600;
        }

        .back-admin-link {
            color: #6c757d;
            font-weight: 700;
            text-decoration: none;
        }

        .back-admin-link:hover {
            color: #0a2540;
            text-decoration: underline;
        }

        .admin-contact-shell {
            min-height: calc(100vh - 140px);
            display: flex;
            flex-direction: column;
        }

        .admin-contact-scroll {
            flex: 1 1 auto;
            overflow-y: auto;
            max-height: calc(100vh - 310px);
            padding-right: .35rem;
        }

        .admin-contact-footer {
            flex: 0 0 auto;
            background: #f8f9fa;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .contact-attachment {
            max-width: 140px;
            max-height: 140px;
            object-fit: cover;
            border-radius: .75rem;
            border: 1px solid #e8edf3;
        }
    </style>

    <div class="contact-page admin-contact-shell">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="contact-title mb-0">Contact Messages</h1>
            <div class="open-pill">{{ $counts['open'] }} open messages</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="contact-tabs d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.contacts.index', ['tab' => 'all', 'status' => $currentStatus]) }}"
                    class="btn {{ $currentTab === 'all' ? 'btn-active' : 'btn-outline-secondary' }}">
                    All
                </a>
                <a href="{{ route('admin.contacts.index', ['tab' => 'customer', 'status' => $currentStatus]) }}"
                    class="btn {{ $currentTab === 'customer' ? 'btn-active' : 'btn-outline-secondary' }}">
                    Customers
                </a>
                <a href="{{ route('admin.contacts.index', ['tab' => 'restaurant', 'status' => $currentStatus]) }}"
                    class="btn {{ $currentTab === 'restaurant' ? 'btn-active' : 'btn-outline-secondary' }}">
                    Restaurants
                </a>
            </div>

            <form method="GET" action="{{ route('admin.contacts.index') }}" class="d-flex align-items-center gap-2">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <label for="status-filter" class="text-muted fw-semibold small mb-0">Status</label>
                <select id="status-filter" name="status" class="form-select form-select-sm status-select"
                    onchange="this.form.submit()">
                    <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>All statuses</option>
                    <option value="open" {{ $currentStatus === 'open' ? 'selected' : '' }}>Open
                        ({{ $counts['open'] ?? 0 }})</option>
                    <option value="replied" {{ $currentStatus === 'replied' ? 'selected' : '' }}>Replied
                        ({{ $counts['replied'] ?? 0 }})</option>
                    <option value="resolved" {{ $currentStatus === 'resolved' ? 'selected' : '' }}>Resolved
                        ({{ $counts['resolved'] ?? 0 }})</option>
                </select>
            </form>
        </div>

        <div class="admin-contact-scroll">
            <div class="d-flex flex-column gap-3">
                @forelse ($contacts as $contact)
                    @php
                        $isRestaurant = (bool) $contact->restaurant_id;
                        $displayName = $isRestaurant
                            ? $contact->restaurant?->restaurant_name
                            : trim(($contact->user?->first_name ?? '') . ' ' . ($contact->user?->last_name ?? ''));
                        $displayName = $displayName ?: $contact->user?->email ?? 'Unknown';
                        $messages = collect([$contact])->merge($contact->replies);
                    @endphp

                    <div class="contact-card {{ $contact->status === 'open' ? 'open-card' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="d-flex gap-3">
                                <div class="avatar-circle {{ $isRestaurant ? 'avatar-restaurant' : 'avatar-customer' }}">
                                    <i class="{{ $isRestaurant ? 'fa-solid fa-store' : 'fa-regular fa-user' }}"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="fw-bold mb-0">{{ $displayName }}</h5>
                                        <span
                                            class="type-badge {{ $isRestaurant ? 'badge-restaurant' : 'badge-customer' }}">
                                            {{ $isRestaurant ? 'Restaurant' : 'Customer' }}
                                        </span>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        <i
                                            class="fa-regular fa-clock me-1"></i>{{ $contact->created_at->format('Y-m-d H:i') }}
                                        @if ($contact->title)
                                            <span class="ms-2 fw-semibold">{{ $contact->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <span class="status-badge status-{{ $contact->status }}">{{ $contact->status }}</span>
                        </div>

                        <p class="mt-3 mb-3" style="white-space: pre-wrap;">{{ $contact->message }}</p>

                        @if ($contact->attachments_list)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($contact->attachments_list as $path)
                                    @if (!empty($path) && is_string($path))
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener">
                                            <img src="{{ asset('storage/' . $path) }}" class="contact-attachment"
                                                alt="Attachment">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if ($contact->replies->count())
                            <div class="d-flex flex-column gap-2 mb-3">
                                @foreach ($contact->replies as $reply)
                                    <div class="{{ $reply->user?->isAdmin() ? 'reply-box' : 'thread-box' }}">
                                        <div class="small fw-bold text-muted mb-1">
                                            {{ $reply->sender_label }} reply ·
                                            {{ $reply->created_at->format('Y-m-d H:i') }}
                                        </div>
                                        <div style="white-space: pre-wrap;">{{ $reply->message }}</div>

                                        @if ($reply->attachments_list)
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @foreach ($reply->attachments_list as $path)
                                                    @if (!empty($path) && is_string($path))
                                                        <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                                            rel="noopener">
                                                            <img src="{{ asset('storage/' . $path) }}"
                                                                class="contact-attachment" alt="Attachment">
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            @if ($contact->status !== 'resolved')
                                <button class="btn btn-navy px-4" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#reply-{{ $contact->id }}">
                                    <i class="fa-regular fa-paper-plane me-2"></i>Reply
                                </button>
                            @endif

                            @if ($contact->status !== 'resolved')
                                <form action="{{ route('admin.contacts.status', $contact) }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" class="btn btn-outline-success">Mark as resolved</button>
                                </form>
                            @else
                                <form action="{{ route('admin.contacts.status', $contact) }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="open">
                                    <button type="submit" class="btn btn-outline-secondary">Reopen</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Delete this contact thread?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                            </form>
                        </div>

                        <div class="collapse mt-3" id="reply-{{ $contact->id }}">
                            <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST"
                                enctype="multipart/form-data" class="border-top pt-3">
                                @csrf
                                <label class="form-label fw-bold">Reply</label>
                                <textarea name="message" class="form-control mb-3" rows="3" placeholder="Type your reply..." required></textarea>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="file" name="attachments[]" class="form-control" multiple
                                        accept="image/*">
                                    <button type="submit" class="btn btn-navy px-4">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="contact-card text-center text-muted py-5">No contacts found.</div>
                @endforelse
            </div>
        </div>

        <div class="admin-contact-footer">
            <a href="{{ route('admin.users') }}" class="back-admin-link">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Admin Dashboard
            </a>
        </div>
    </div>
@endsection
