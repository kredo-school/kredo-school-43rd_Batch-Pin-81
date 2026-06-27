@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<style>
.page-title{
    color:#0a2540;
    font-size:48px;
    font-weight:700;
}

.btn-dark-blue{
    background-color: #0a2540;
    color: #fff !important;
}

.btn-dark-blue:hover{
    background-color:#0a2540;
    color:#fff !important;
}

.btn-unactive {
    background: #fff;
    color: #0a2540;
    border: 1px solid #0a2540;
}

.btn-unactive:hover{
    background-color: #0a2540;
    color: #fff !important;
}

.btn-dark-blue:active,
.btn-dark-blue.active,
.btn-dark-blue:focus,
.btn-dark-blue:focus-visible {
    background-color: #0a2540 !important;
    color: #fff !important;
    border-color: #0a2540 !important;
    box-shadow: none !important;
}

.btn-unactive:active,
.btn-unactive.active {
    background-color: #0a2540 !important;
    color: #fff !important;
    border-color: #0a2540 !important;
}

.avatar-circle{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#eef1f5;

    display:flex;
    align-items:center;
    justify-content:center;

    font-weight:600;
    color:#6b7280;
}

.table th{
    font-size:13px;
    color:#6b7280;
    padding:1rem 1.5rem;
}

.table td{
    padding:1rem 1.5rem;
}

.role-customer{
    background:#dbeafe;
    color:#2563eb;
}

.role-restaurant-owner{
    background:#ffedd5;
    color:#ea580c;
}

.role-admin{
    background:#f3e8ff;
    color:#9333ea;
}

.role-customer,
.role-restaurant-owner,
.role-admin{
    border: none;
    padding: .5rem .75rem;
    border-radius: 999px;
}

.dropdown-menu{
    border: none;
    border-radius: 16px;
    padding: .5rem;
    min-width: 180px;
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.role-option{
    display: block;
    width: 100%;
    text-align: left;
    border: none;
    background: transparent;
    padding: .6rem .8rem;
    border-radius: 10px;
    font-weight: 500;
}

/* Customer */
.role-option-customer{
    color: #2563eb;
}

.role-option-customer:hover{
    background: #dbeafe;
    color: #2563eb;
}

/* Restaurant Owner */
.role-option-restaurant-owner{
    color: #ea580c;
}

.role-option-restaurant-owner:hover{
    background: #ffedd5;
    color: #ea580c;
}

/* Admin */
.role-option-admin{
    color: #9333ea;
}

.role-option-admin:hover{
    background: #f3e8ff;
    color: #9333ea;
}

.status-active{
    background:#dcfce7;
    color:#16a34a;
}

.status-suspended{
    background:#fee2e2;
    color:#dc2626;
}

.status-option{
    display:block;
    width:100%;
    text-align:left;
    border:none;
    background:transparent;
    padding:.6rem .8rem;
    border-radius:10px;
    font-weight:500;
}

.status-option-active{
    color:#16a34a;
}

.status-option-active:hover{
    background:#dcfce7;
}

.status-option-suspended{
    color:#dc2626;
}

.status-option-suspended:hover{
    background:#fee2e2;
}

/* Modal style */
.modal-content{
    border-radius: 20px;
    border: none;
}

.modal-header{
    border-bottom: 1px solid #e5e7eb;
}

.modal-footer{
    border-top: 1px solid #e5e7eb;
}

</style>

<div class="container-fluid">

    <h1 class="fw-bold mb-4 page-title">
        Users
    </h1>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <!-- Filter Buttons -->
    <div class="mb-4">

        <a href="{{ route('admin.users') }}"
           class="btn {{ request()->routeIs('admin.users') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">
            All Users
        </a>

        <a href="{{ route('admin.users.customers') }}"
           class="btn {{ request()->routeIs('admin.users.customers') ? 'btn-dark-blue' : 'btn-unactive' }} me-2">
            Customers
        </a>

        <a href="{{ route('admin.users.restaurants') }}"
           class="btn {{ request()->routeIs('admin.users.restaurants') ? 'btn-dark-blue' : 'btn-unactive' }}">
            Restaurants Owner
        </a>

        <a href="{{ route('admin.users.admin') }}"
           class="btn {{ request()->routeIs('admin.users.admin') ? 'btn-dark-blue' : 'btn-unactive' }}">
            Admin
        </a>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light">

                    <tr>
                        <th>USER</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>JOINED</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr>

                        <!-- User -->
                        <td>

                            <div class="d-flex align-items-center">

                                <div class="avatar-circle me-3">

                                    {{ strtoupper(substr($user->first_name,0,1)) }}

                                </div>

                                <div>

                                    <strong>
                                        {{ $user->first_name }}
                                        {{ $user->last_name }}
                                    </strong>

                                </div>

                            </div>

                        </td>

                        <!-- Email -->
                        <td>
                            {{ $user->email }}
                        </td>

                        <!-- Role -->
                        <td>

                            <div class="dropdown">

                                <button
                                    type="button"
                                    class="badge border-0 dropdown-toggle

                                    @if($user->role_id == 1)
                                        role-customer
                                    @elseif($user->role_id == 2)
                                        role-restaurant-owner
                                    @else
                                        role-admin
                                    @endif"

                                    data-bs-toggle="dropdown">

                                    @if($user->role_id == 1)
                                        customer
                                    @elseif($user->role_id == 2)
                                        restaurant owner
                                    @else
                                        admin
                                    @endif

                                </button>

                                <ul class="dropdown-menu">

                                    <li>
                                        <form action="{{ route('admin.users.role', $user) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                  name="role_id"
                                                  value="1">

                                            <button type="submit"
                                                    class="role-option role-option-customer">
                                                
                                                @if($user->role_id == 1)
                                                    ✓
                                                @endif
                                                Customer
                                            </button>
                                        </form>
                                    </li>

                                    <li>
                                        <form action="{{ route('admin.users.role', $user) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                  name="role_id"
                                                  value="2">

                                            <button type="submit"
                                                    class="role-option role-option-restaurant-owner">
                                                
                                                @if($user->role_id == 2)
                                                    ✓
                                                @endif
                                                Restaurant Owner
                                            </button>
                                        </form>
                                    </li>

                                    <li>
                                        <form action="{{ route('admin.users.role', $user) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                  name="role_id"
                                                  value="3">

                                            <button type="submit"
                                                    class="role-option role-option-admin">
                                                
                                                @if($user->role_id == 3)
                                                    ✓
                                                @endif
                                                Admin
                                            </button>
                                        </form>
                                    </li>

                                </ul>

                            </div>

                        </td>

                        <!-- Status -->
                        <td>

                             <div class="dropdown">

                                <button
                                    type="button"
                                    class="badge border-0 dropdown-toggle
                                        {{ $user->is_active ? 'status-active' : 'status-suspended' }}"
                                    data-bs-toggle="dropdown">

                                    ● {{ $user->is_active ? 'active' : 'suspended' }}

                                </button>

                                <ul class="dropdown-menu">

                                    <li>
                                        <form action="{{ route('admin.users.status', $user) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                  name="is_active"
                                                  value="1">

                                            <button type="submit"
                                                    class="status-option status-option-active">
                                                @if($user->is_active)
                                                    ✓
                                                @endif
                                                Active
                                            </button>

                                        </form>
                                    </li>

                                    <li>
                                        <form action="{{ route('admin.users.status', $user) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                  name="is_active"
                                                  value="0">

                                            <button type="submit"
                                                    class="status-option status-option-suspended">
                                                @if(!$user->is_active)
                                                    ✓
                                                @endif
                                                Suspended
                                            </button>

                                        </form>
                                    </li>

                                </ul>

                            </div>

                        </td>

                        <!-- Joined -->
                        <td>
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>

                        <!-- Delete -->
                        <td>

                            <button type="button"
                                    class="btn text-danger border-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $user->id }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>

                        </td>

                    </tr>

                    {{-- include  the modal here --}}
                    @include('admin.modals.delete', [
                        'id' => $user->id,
                        'route' => route('admin.users.destroy', $user),
                        'title' => 'Delete User',
                        'message' => 'Are you sure you want to delete ' . $user->first_name . ' ' . $user->last_name . '?'
                    ])

                    @empty

                    <tr>

                        <td colspan="6" class="text-center py-4">
                            No users found.
                        </td>

                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@endsection

