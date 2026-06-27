@extends('layouts.admin')

@section('title', 'Restaurant Edit')

@section('content')

<style>
.page-title{
    color:#0a2540;
    font-size:48px;
    font-weight:700;
}

.input-box:focus {
    border-color: #cfb2c4;
    box-shadow: 0 0 0 0.2rem rgba(233, 192, 228, 0.25);
}

.btn-dark-blue{
    background-color:#0a2540 !important;
    color:#fff !important;
}

.btn-dark-blue:hover{
    background-color:#294664 !important;
    color:#fff !important;
}
</style>

<div class="container">

    <h1 class="mt-5 mb-4 page-title">Edit Restaurant</h1>

    <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- RESTAURANT INFO --}}
        <div class="card mb-3 p-3">
            <h4>Restaurant Info</h4>

            <input type="text" name="restaurant_name"
                  value="{{ $restaurant->restaurant_name }}"
                  class="form-control mb-2 input-box"
                  placeholder="Restaurant Name">

            <textarea name="description"
                      class="form-control mb-2 input-box"
                      placeholder="Description">{{ $restaurant->description }}</textarea>

            <input type="text" name="address"
                  value="{{ $restaurant->address }}"
                  class="form-control mb-2 input-box"
                  placeholder="Address">

            <input type="text" name="phone_number"
                  value="{{ $restaurant->phone_number }}"
                  class="form-control mb-2 input-box"
                  placeholder="Phone Number">
        </div>

        {{-- SOCIAL --}}
        <div class="card mb-3 p-3">
            <h4>Social Links</h4>

            <input type="text" name="website"
                  value="{{ $restaurant->website }}"
                  class="form-control mb-2 input-box"
                  placeholder="Website">

            <input type="text" name="instagram"
                  value="{{ $restaurant->instagram }}"
                  class="form-control mb-2 input-box"
                  placeholder="Instagram">

            <input type="text" name="facebook"
                  value="{{ $restaurant->facebook }}"
                  class="form-control mb-2 input-box"
                  placeholder="Facebook">

            <input type="text" name="twitter"
                  value="{{ $restaurant->twitter }}"
                  class="form-control mb-2 input-box"
                  placeholder="Twitter">
        </div>

        {{-- SETTINGS --}}
        <div class="card mb-3 p-3">
            <h4>Settings</h4>

            <input type="number" name="capacity"
                  value="{{ $restaurant->capacity }}"
                  class="form-control mb-2 input-box"
                  placeholder="Capacity">

            {{-- Opening hours --}}

            @php
            $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            @endphp

            <div class="card p-3 mb-3">
                <h4>Operating Hours</h4>

                <div id="hours-container">

                    @foreach($days as $day)
                        <div class="p-3">

                            <h5 class="text-capitalize">{{ $day }}</h5>

                            <div class="slots" data-day="{{ $day }}">

                                @forelse($restaurant->operating_hours[$day] ?? [] as $slot)

                                    <div class="d-flex gap-2 mb-2 slot-row">
                                        <input type="time"
                                              name="hours[{{ $day }}][open][]"
                                              value="{{ $slot['open'] }}"
                                              class="form-control">

                                        <input type="time"
                                              name="hours[{{ $day }}][close][]"
                                              value="{{ $slot['close'] }}"
                                              class="form-control">

                                        <button type="button" class="btn btn-danger btn-sm remove-slot">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                @empty

                                    <div class="d-flex gap-2 mb-2 slot-row">
                                        <input type="time"
                                              name="hours[{{ $day }}][open][]"
                                              class="form-control input-box">

                                        <input type="time"
                                              name="hours[{{ $day }}][close][]"
                                              class="form-control input-box">

                                        <button type="button" class="btn btn-danger btn-sm remove-slot">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                @endforelse

                            </div>

                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary add-slot"
                                    data-day="{{ $day }}">
                                <i class="fa-solid fa-plus"></i>
                            </button>

                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-dark-blue px-3">
                Save Changes
            </button>
        </div>
        
    </form>

</div>


<script>
document.addEventListener('click', function (e) {

    // safer: check closest button instead of direct target
    const addBtn = e.target.closest('.add-slot');

    if (addBtn) {
        const day = addBtn.dataset.day;
        const container = document.querySelector(`.slots[data-day="${day}"]`);

        const html = `
            <div class="d-flex gap-2 mb-2 slot-row">
                <input type="time" name="hours[${day}][open][]" class="form-control input-box">
                <input type="time" name="hours[${day}][close][]" class="form-control input-box">
                <button type="button" class="btn btn-danger btn-sm remove-slot"><i class="fa-solid fa-xmark"></i></button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
    }

    const removeBtn = e.target.closest('.remove-slot');

    if (removeBtn) {
        removeBtn.closest('.slot-row').remove();
    }

});
</script>

@endsection