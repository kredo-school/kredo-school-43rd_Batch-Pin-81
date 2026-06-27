@extends('layouts.restaurant')

@section('title', 'Restaurant Information')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            color: #0f2d4a;
        }

        .profile-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .text-navy {
            color: #0f2d4a !important;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f2d4a;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #0f2d4a;
            font-size: 0.95rem;
            margin-bottom: 0.4rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            color: #0f2d4a;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #0f2d4a;
            box-shadow: none;
        }

        .form-check-input:checked {
            background-color: #0f2d4a;
            border-color: #0f2d4a;
        }

        .btn-save {
            background-color: #0f2d4a;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: background-color 0.2s;
        }

        .btn-save:hover {
            background-color: #173b5e;
            color: #fff;
        }

        .add-shift-btn {
            color: #0f2d4a;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            background: none;
            border: none;
            padding: 0;
        }

        .add-shift-btn:hover {
            text-decoration: underline;
        }

        .time-picker-wrapper {
            position: relative;
        }

        .time-picker-wrapper .clock-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #0f2d4a;
            pointer-events: none;
            font-size: 0.95rem;
        }

        .time-input::-webkit-calendar-picker-indicator {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .time-input {
            padding-right: 35px !important;
        }

        .remove-shift-btn {
            color: #dc3545;
            background: none;
            border: none;
            padding: 0;
            margin-top: 24px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
        }

        .remove-shift-btn:hover {
            color: #dc3545;
            background-color: #fde8e8;
        }
    </style>

    <div class="container pb-5" style="max-width: 1140px;">
        <h2 class="fw-bold mb-4 text-navy" style="font-size: 28px;">Restaurant Information</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('restaurant.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. 基本情報セクション --}}
            <div class="profile-card">
                <div class="section-title">Basic Information</div>

                <div class="mb-3">
                    <label for="name" class="form-label">Restaurant Name</label>
                    <input type="text" class="form-control" id="restaurant_name" name="restaurant_name"
                        value="{{ $restaurant->restaurant_name }}">
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description (English)</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $restaurant->description }}</textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label d-block">Cuisine Type (Select all that apply)</label>
                    <div class="row g-2">
                        @php
                            $cuisines = ['Japanese', 'Korean', 'Italian', 'Chinese', 'French', 'Cafe'];
                        @endphp
                        @foreach ($cuisines as $cuisine)
                            <div class="col-md-4 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cuisine_types[]"
                                        value="{{ $cuisine }}" id="cuisine-{{ $loop->index }}"
                                        {{ in_array($cuisine, $restaurant->cuisine_types ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label text-navy" for="cuisine-{{ $loop->index }}">
                                        {{ $cuisine }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. 連絡先セクション --}}
            <div class="profile-card">
                <div class="section-title">Contact Information</div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ $restaurant->address }}">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            value="{{ $restaurant->phone_number }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $restaurant->email }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="text" class="form-control" name="website" value="{{ $restaurant->website }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Instagram</label>
                    <input type="text" class="form-control" name="instagram" value="{{ $restaurant->instagram }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Facebook</label>
                    <input type="text" class="form-control" name="facebook" value="{{ $restaurant->facebook }}">
                </div>

                <div class="mb-1">
                    <label class="form-label">X (Twitter)</label>
                    <input type="text" class="form-control" name="twitter" value="{{ $restaurant->twitter }}">
                </div>
            </div>

            {{-- 3. 営業時間セクション (改良版) --}}
            <div class="profile-card">
                <div class="section-title">Operating Hours</div>

                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    @php
                        $dayHours = $restaurant->hours[$day] ?? ['open' => '', 'close' => '', 'closed' => false];
                        $dayLower = strtolower($day);
                    @endphp
                    <div class="row align-items-start mb-4">
                        <div class="col-md-2 col-12 mb-2 mb-md-0 pt-2">
                            <span class="fw-bold text-navy">{{ $day }}</span>
                        </div>

                        <div class="col-md-8 col-8">
                            {{-- シフトリストを囲うコンテナ --}}
                            <div class="time-inputs-container" id="{{ $dayLower }}-time-container">

                                {{-- 1つ目の時間設定枠 --}}
                                <div class="time-input-row d-flex align-items-center gap-3 mb-2">
                                    <div class="flex-grow-1">
                                        <span class="small text-muted d-block mb-1"
                                            style="font-size: 0.75rem;">Open</span>
                                        <div class="position-relative time-picker-wrapper">
                                            <input type="time" class="form-control time-input"
                                                name="hours[{{ $day }}][0][open]"
                                                value="{{ $dayHours['open'] }}">
                                            <i class="bi bi-clock clock-icon"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="small text-muted d-block mb-1"
                                            style="font-size: 0.75rem;">Close</span>
                                        <div class="position-relative time-picker-wrapper">
                                            <input type="time" class="form-control time-input"
                                                name="hours[{{ $day }}][0][close]"
                                                value="{{ $dayHours['close'] }}">
                                            <i class="bi bi-clock clock-icon"></i>
                                        </div>
                                    </div>
                                    {{-- 1つ目の枠の右側にも設置したゴミ箱アイコン --}}
                                    <button type="button" class="remove-shift-btn" title="Clear time">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>

                            </div>

                            {{-- 日曜日以外に「+ Add break」ボタンを配置 --}}
                            @if ($day !== 'Sunday')
                                <div class="mt-2">
                                    <button type="button" class="add-shift-btn" data-day="{{ $dayLower }}"
                                        data-day-name="{{ $day }}">
                                        <i class="bi bi-plus"></i> Add break / second shift
                                    </button>
                                    
                                </div>
                            @endif
                        </div>

                        <div class="col-md-2 col-4 text-end pt-4">
                            <div class="form-check d-inline-block">
                                <input class="form-check-input" type="checkbox"
                                    name="hours[{{ $day }}][closed]" value="1"
                                    id="closed-{{ $day }}" {{ $dayHours['closed'] ? 'checked' : '' }}>
                                <label class="form-check-label text-navy small fw-semibold"
                                    for="closed-{{ $day }}">
                                    Closed
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4 pt-3">
                    <label class="form-label small fw-bold" style="color: #0A2540;">Reservation Slot Duration</label>
                    <select name="stay_duration" class="form-select form-custom-input">
                        <option value="60" {{ $restaurant->stay_duration == 60 ? 'selected' : '' }}>1 Hour</option>
                        <option value="90" {{ $restaurant->stay_duration == 90 ? 'selected' : '' }}>1.5 Hours</option>
                        <option value="120" {{ $restaurant->stay_duration == 120 ? 'selected' : '' }}>2 Hours</option>
                        <option value="150" {{ $restaurant->stay_duration == 150 ? 'selected' : '' }}>2.5 Hours</option>
                        <option value="180" {{ $restaurant->stay_duration == 180 ? 'selected' : '' }}>3 Hours</option>
                    </select>
                    <small class="text-muted d-block mt-1">Select the default time slot allocated for each customer group.</small>
                </div>

            </div>

            {{-- 4. 特徴セクション --}}
            <div class="profile-card">
                <div class="section-title">Restaurant Features</div>
                <div class="d-flex flex-column gap-3">
                    @php
                        $featuresList = [
                            'english_menu' => 'English Menu Available',
                            'credit_cards' => 'Credit Cards Accepted',
                            'reservations_required' => 'Reservations Required',
                            'english_speaking_staff' => 'English Speaking Staff',
                            'vegetarian_options' => 'Vegetarian Options',
                            'halal_options' => 'Halal Options',
                        ];
                    @endphp
                    @foreach ($featuresList as $key => $label)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-navy">{{ $label }}</span>
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    name="features[{{ $key }}]" value="1"
                                    {{ $restaurant->features[$key] ?? false ? 'checked' : '' }}>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 5. キャパシティ設定セクション --}}
            <div class="profile-card">
                <div class="section-title">Capacity Settings</div>
                <div class="mb-1">
                    <label for="capacity" class="form-label">Maximum Party Size</label>
                    <input type="number" class="form-control" id="capacity" name="capacity"
                        value="{{ $restaurant->capacity }}">
                </div>
            </div>

            <div class="mb-5">
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>

    {{-- ⚙️ 制御用JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. シフト追加ボタンのイベントリスナー
            document.querySelectorAll('.add-shift-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const dayKey = this.getAttribute('data-day');
                    const dayName = this.getAttribute('data-day-name');
                    const container = document.getElementById(`${dayKey}-time-container`);

                    // 現在の入力枠数をチェック
                    const currentRows = container.querySelectorAll('.time-input-row').length;

                    // 最大2つの時間枠（営業＋業務外）までに制限
                    if (currentRows >= 2) {
                        alert('You can add a maximum of 2 time shifts.');
                        return;
                    }

                    // 新しい時間枠行を生成（右側にゴミ箱アイコン付き）
                    const newRow = document.createElement('div');
                    newRow.className = 'time-input-row d-flex align-items-center gap-3 mb-2';
                    newRow.innerHTML = `
                <div class="flex-grow-1">
                    <div class="position-relative time-picker-wrapper">
                        <input type="time" class="form-control time-input" name="hours[${dayName}][${currentRows}][open]">
                        <i class="bi bi-clock clock-icon"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="position-relative time-picker-wrapper">
                        <input type="time" class="form-control time-input" name="hours[${dayName}][${currentRows}][close]">
                        <i class="bi bi-clock clock-icon"></i>
                    </div>
                </div>
                <button type="button" class="remove-shift-btn" style="margin-top: 0;" title="Remove shift">
                    <i class="bi bi-trash fs-5"></i>
                </button>
            `;

                    container.appendChild(newRow);
                });
            });

            // 2. ゴミ箱ボタンの削除・クリア処理（動的追加要素にも対応するイベント委譲）
            document.body.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-shift-btn');
                if (removeBtn) {
                    const row = removeBtn.closest('.time-input-row');
                    const container = row.closest('.time-inputs-container');
                    const allRows = container.querySelectorAll('.time-input-row');

                    if (allRows.length > 1) {
                        // 2つ以上枠がある場合は、その行自体を削除する
                        row.remove();
                    } else {
                        // 最後の1つの場合は行を消さず、入力値をクリアする（写真の1行目のゴミ箱の挙動をカバー）
                        row.querySelectorAll('input[type="time"]').forEach(input => input.value = '');
                    }
                }
            });
        });
    </script>
@endsection