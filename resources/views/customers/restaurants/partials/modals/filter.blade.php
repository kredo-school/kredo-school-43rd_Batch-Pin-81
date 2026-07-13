<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content" style="background-color: #fffefc; font-family: inter;">

            <button
                type="button"
                class="btn-close position-absolute top-0 end-0 m-3"
                data-bs-dismiss="modal"
                aria-label="Close">
            </button>

            <form id="filterForm" 
                class="d-flex justify-content-between"
                method="GET"
                action="{{ $filterAction ?? url('/restaurants/view') }}">

                <!-- Body -->
                <div class="modal-body mt-5 mx-4 mb-2" style="color: #0a2540; font-family: inter">

                    {{-- Cuisine --}}
                    @php
                        $cuisines = [
                            'Japanese',
                            'Korean',
                            'Italian',
                            'Chinese',
                            'French',
                            'Cafe',
                        ];
                    @endphp

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-4">Cuisine</h6>

                        <div class="row g-2">
                            @foreach ($cuisines as $cuisine)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input custom-checkbox"
                                            type="checkbox"
                                            id="{{ Str::slug($cuisine) }}"
                                            name="cuisines[]"
                                            value="{{ $cuisine }}"
                                            {{ in_array($cuisine, request('cuisines', [])) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="{{ Str::slug($cuisine) }}"
                                        >
                                            {{ $cuisine }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                

                    <hr>

                    {{-- Features --}}
                    @php
                        $features = [
                            'English Menu',
                            'Online Payment',
                            'Credit Cards',
                            'Takeout Available',
                            'Free Wi-Fi',
                            'Parking Available',
                        ];
                    @endphp

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Features</h6>

                        <div class="row">
                            @foreach ($features as $feature)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input custom-checkbox"
                                            type="checkbox"
                                            id="{{ Str::slug($feature) }}"
                                            name="features[]"
                                            value="{{ $feature }}"
                                            {{ in_array($feature, request('features', [])) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="{{ Str::slug($feature) }}"
                                        >
                                            {{ $feature }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    {{-- Rating --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Minimum Rating</h6>

                        <select name="rating" class="form-select input-box">
                            <option value="">Any Rating</option>
                            <option value="4.0+" {{ request('rating') == '4.0+' ? 'selected' : '' }}>4.0+</option>
                            <option value="4.5+" {{ request('rating') == '4.5+' ? 'selected' : '' }}>4.5+</option>
                        </select>
                    </div>

                    <hr>

                    {{-- Distance --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-3">Distance</h6>

                        <select name="distance" class="form-select input-box">
                            <option value="">Any Distance</option>
                            <option value="Within 1 km" {{ request('distance') == 'Within 1 km' ? 'selected' : '' }}>Within 1 km</option>
                            <option value="Within 5 km" {{ request('distance') == 'Within 5 km' ? 'selected' : '' }}>Within 5 km</option>
                            <option value="Within 10 km" {{ request('distance') == 'Within 10 km' ? 'selected' : '' }}>Within 10 km</option>
                        </select>
                    </div>

                    
                    <div class="d-flex justify-content-end mt-4">
                        <button
                            type="button"
                            id="clearFilters"
                            class="btn btn-link text-muted text-decoration-none">
                            Clear All
                        </button>

                        <button type="submit" class="btn apply-filter-btn">
                            Apply Filters
                        </button>
                    </div>
                    
                </div>


            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('clearFilters').addEventListener('click', function () {

        // Uncheck all checkboxes
        document.querySelectorAll('#filterForm input[type="checkbox"]')
            .forEach(cb => cb.checked = false);

        // Reset selects
        document.querySelectorAll('#filterForm select')
            .forEach(select => select.selectedIndex = 0);

    });
</script>

<style>
    .btn-close:focus {
        box-shadow: 0 0 0 0.15rem rgba(0,0,0,.15) !important;
    }

    .custom-checkbox:checked {
        background-color: #8d4b75 !important;
        border-color: #ca9cb9 !important;
    }

    .custom-checkbox:focus {
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25) !important;
        border-color: #ca9cb9 !important;
    }
    
    .input-box:focus {
        border-color: #cfb2c4 !important;
        box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25) !important;
    }

    /* Buttons */
    .apply-filter-btn {
        background-color: #FCE7F3 !important;
        color: #0a2540 !important; /* text color */
        cursor: pointer !important;
        transition: 0.3s !important;
    }

    /* mouse hover effect */
    .apply-filter-btn:hover {
        background-color: #fdd6eb !important;
        color: #0a2a5e !important;
    }

</style>