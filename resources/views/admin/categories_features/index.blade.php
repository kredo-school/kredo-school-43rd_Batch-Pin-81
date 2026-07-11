@extends('layouts.admin')

@section('content')

    <style>
        .page-title {
            color: #0a2540;
            font-size: 48px;
            font-weight: 700;
        }

        .btn-dark-blue {
            background-color: #0a2540;
            color: #fff !important;
        }

        .btn-dark-blue:hover {
            background-color: #0a2540;
            color: #fff !important;
        }

        .table th {
            font-size: 13px;
            color: #6b7280;
            padding: 1rem 1.5rem;
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
        }

        .item-badge {
            background: #eef1f5;
            color: #0a2540;
            border-radius: 999px;
            padding: .4rem .75rem;
            font-size: 13px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Categories & Features</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">Categories</h4>
                            <p class="text-secondary mb-0">Manage restaurant cuisine categories</p>
                        </div>

                        <button type="button" class="btn btn-dark-blue rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="fa-solid fa-plus me-1"></i>
                            Add
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <thead class="border-top">
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Restaurants</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>#{{ $category->id }}</td>
                                        <td>
                                            <span class="item-badge">{{ $category->category_name }}</span>
                                        </td>
                                        <td>{{ $category->restaurants_count }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal{{ $category->id }}">
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal{{ $category->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-5">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">Features</h4>
                            <p class="text-secondary mb-0">Manage restaurant features</p>
                        </div>

                        <button type="button" class="btn btn-dark-blue rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#addFeatureModal">
                            <i class="fa-solid fa-plus me-1"></i>
                            Add
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <thead class="border-top">
                                <tr>
                                    <th>ID</th>
                                    <th>Feature Name</th>
                                    <th>Restaurants</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($features as $feature)
                                    <tr>
                                        <td>#{{ $feature->id }}</td>
                                        <td>
                                            <span class="item-badge">{{ $feature->feature_name }}</span>
                                        </td>
                                        <td>{{ $feature->restaurants_count }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editFeatureModal{{ $feature->id }}">
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteFeatureModal{{ $feature->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-5">No features found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Category Modal --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="category_name" class="form-control" placeholder="e.g. Japanese"
                            value="{{ old('category_name') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark-blue rounded-pill">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Feature Modal --}}
    <div class="modal fade" id="addFeatureModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.features.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Feature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-bold">Feature Name</label>
                        <input type="text" name="feature_name" class="form-control" placeholder="e.g. Vegan Friendly"
                            value="{{ old('feature_name') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark-blue rounded-pill">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($categories as $category)
        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label fw-bold">Category Name</label>
                            <input type="text" name="category_name" class="form-control"
                                value="{{ old('category_name', $category->category_name) }}" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark-blue rounded-pill">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Delete Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong>{{ $category->category_name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($features as $feature)
        <div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.features.update', $feature) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Feature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label fw-bold">Feature Name</label>
                            <input type="text" name="feature_name" class="form-control"
                                value="{{ old('feature_name', $feature->feature_name) }}" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark-blue rounded-pill">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteFeatureModal{{ $feature->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Delete Feature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong>{{ $feature->feature_name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection
