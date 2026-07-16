@extends('layouts.admin')

@section('title', 'Categories, Features & Areas')

@section('content')

    <style>
        .page-title {
            color: #0a2540;
            font-size: 32px;
            font-weight: 700;
        }

        .btn-dark-blue {
            background-color: #0a2540;
            color: #fff !important;
            border-radius: 8px;
        }

        .btn-dark-blue:hover {
            background-color: #0a2540;
            color: #fff !important;
        }

        .table th {
            font-size: 11px;
            color: #495057;
            padding: 1rem 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .item-badge {
            background: #eef1f5;
            color: #0a2540;
            border-radius: 999px;
            padding: .4rem .75rem;
            font-size: 13px;
            font-weight: 600;
        }

        .area-preview-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .scrollable-list {
            max-height: 670px;
            overflow-y: auto;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Categories, Features & Areas</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Column 1: Categories --}}
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #0a2540;">Categories</h4>
                            <p class="text-secondary small mb-0">Manage cuisine categories</p>
                        </div>

                        <button type="button" class="btn btn-dark-blue btn-sm px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="fa-solid fa-plus me-1"></i> Add
                        </button>
                    </div>

                    <div class="card-body p-0 scrollable-list">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="table-layout: fixed; width: 100%;">
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th class="ps-3" style="width: 15%;">ID</th>
                                        <th style="width: 35%;">Name</th>
                                        <th style="width: 18%;">Count</th>
                                        <th class="text-end pe-3" style="width: 32%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td class="ps-3 text-secondary fw-bold text-nowrap">#{{ $category->id }}</td>
                                            <td>
                                                <span class="item-badge d-inline-block text-truncate max-w-100"
                                                    title="{{ $category->category_name }}">{{ $category->category_name }}</span>
                                            </td>
                                            <td class="text-secondary fw-semibold ps-4">{{ $category->restaurants_count }}
                                            </td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-secondary me-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCategoryModal{{ $category->id }}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteCategoryModal{{ $category->id }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">No categories found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Features --}}
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #0a2540;">Features</h4>
                            <p class="text-secondary small mb-0">Manage restaurant features</p>
                        </div>

                        <button type="button" class="btn btn-dark-blue btn-sm px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#addFeatureModal">
                            <i class="fa-solid fa-plus me-1"></i> Add
                        </button>
                    </div>

                    <div class="card-body p-0 scrollable-list">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="table-layout: fixed; width: 100%;">
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th class="ps-3" style="width: 15%;">ID</th>
                                        <th style="width: 35%;">Name</th>
                                        <th style="width: 18%;">Count</th>
                                        <th class="text-end pe-3" style="width: 32%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($features as $feature)
                                        <tr>
                                            <td class="ps-3 text-secondary fw-bold text-nowrap">#{{ $feature->id }}</td>
                                            <td>
                                                <span class="item-badge d-inline-block text-truncate max-w-100"
                                                    title="{{ $feature->feature_name }}">{{ $feature->feature_name }}</span>
                                            </td>
                                            <td class="text-secondary fw-semibold ps-4">{{ $feature->restaurants_count }}
                                            </td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-secondary me-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editFeatureModal{{ $feature->id }}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteFeatureModal{{ $feature->id }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">No features found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 3: Areas --}}
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1" style="color: #0a2540;">Areas</h4>
                            <p class="text-secondary small mb-0">Manage restaurant areas</p>
                        </div>

                        <button type="button" class="btn btn-dark-blue btn-sm px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#addAreaModal">
                            <i class="fa-solid fa-plus me-1"></i> Add
                        </button>
                    </div>

                    <div class="card-body p-0 scrollable-list">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" style="table-layout: fixed; width: 100%;">
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th class="ps-3" style="width: 18%;">ID</th>
                                        <th style="width: 16%;">Img</th>
                                        <th style="width: 34%;">Name</th>
                                        <th class="text-end pe-3" style="width: 32%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($areas as $key => $area)
                                        <tr>
                                            <td class="ps-3 text-secondary fw-bold text-nowrap">
                                                #{{ $areas->count() - $loop->index }}</td> {{-- 以前 #{{ $area->id }} ➡DB IDをそのまま保存 --}}
                                            <td>
                                                @if ($area->image_url)
                                                    <img src="{{ $area->image_url }}" alt="{{ $area->name }}"
                                                        class="area-preview-img" title="{{ $area->image_url }}">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-secondary border"
                                                        style="width: 45px; height: 45px;">
                                                        <i class="fa-solid fa-image" style="font-size: 13px;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark d-inline-block text-truncate w-100"
                                                    style="font-size: 14px;" title="{{ $area->area_name }}">
                                                    {{ $area->area_name }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 text-nowrap">
                                                <button type="button" class="btn btn-sm btn-outline-secondary me-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editAreaModal{{ $area->id }}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteAreaModal{{ $area->id }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">No areas found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODALS ==================== --}}

    {{-- Add Category Modal --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" style="color: #0a2540;">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Category Name</label>
                            <input type="text" name="category_name" class="form-control" placeholder="e.g. Japanese"
                                value="{{ old('category_name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white btn-sm px-4"
                            style="background-color: #0a2540;">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Feature Modal --}}
    <div class="modal fade" id="addFeatureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.features.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" style="color: #0a2540;">Add Feature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Feature Name</label>
                            <input type="text" name="feature_name" class="form-control"
                                placeholder="e.g. Vegan Friendly" value="{{ old('feature_name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white btn-sm px-4"
                            style="background-color: #0a2540;">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Area Modal --}}
    <div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.areas.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" style="color: #0a2540;">Add New Area</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Area Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="area_name" placeholder="e.g. Kyoto"
                                value="{{ old('area_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Image URL (Optional)</label>
                            <input type="url" class="form-control" name="image_url"
                                placeholder="https://images.unsplash.com/..." value="{{ old('image_url') }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white btn-sm px-4"
                            style="background-color: #0a2540;">Create Area</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Category Edit / Delete Modals --}}
    @foreach ($categories as $category)
        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #0a2540;">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Category Name</label>
                                <input type="text" name="category_name" class="form-control"
                                    value="{{ old('category_name', $category->category_name) }}" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white btn-sm px-4"
                                style="background-color: #0a2540;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #000;">Delete Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            Are you sure you want to delete <strong>{{ $category->category_name }}</strong>?
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm px-4">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Feature Edit / Delete Modals --}}
    @foreach ($features as $feature)
        <div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #0a2540;">Edit Feature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Feature Name</label>
                                <input type="text" name="feature_name" class="form-control"
                                    value="{{ old('feature_name', $feature->feature_name) }}" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white btn-sm px-4"
                                style="background-color: #0a2540;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteFeatureModal{{ $feature->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.features.destroy', $feature->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #000;">Delete Feature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            Are you sure you want to delete <strong>{{ $feature->feature_name }}</strong>?
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm px-4">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Area Edit / Delete Modals --}}
    @foreach ($areas as $area)
        <div class="modal fade" id="editAreaModal{{ $area->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.areas.update', $area->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #0a2540;">Edit Area</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Area Name</label>
                                <input type="text" class="form-control" name="area_name"
                                    value="{{ old('area_name', $area->area_name ?? $area->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Image URL</label>
                                <input type="url" class="form-control" name="image_url"
                                    value="{{ old('image_url', $area->image_url) }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white btn-sm px-4"
                                style="background-color: #0a2540;">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteAreaModal{{ $area->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.areas.destroy', $area->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" style="color: #000;">Delete Area</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-3">
                            Are you sure you want to delete <strong>{{ $area->name }}</strong>?
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light btn-sm px-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm px-4">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection
