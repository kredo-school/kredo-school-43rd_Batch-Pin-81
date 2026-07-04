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
            display: flex;
            align-items: center;
        }

        .time-picker-wrapper .clock-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #0f2d4a;
            font-size: 0.95rem;
            cursor: pointer;
            z-index: 2;
        }

        .time-input::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 0;
            top: 0;
            width: 35px;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        .time-input {
            padding-right: 38px !important;
            z-index: 0;
        }

        .remove-shift-btn {
            color: #dc3545;
            background: none;
            border: none;
            padding: 0;
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

        /* 💡 モーダルのボタンスタイルをブランドカラーに調整 */
        .btn-navy-confirm {
            background-color: #0f2d4a;
            color: #fff;
        }
        .btn-navy-confirm:hover {
            background-color: #173b5e;
            color: #fff;
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
                    <label for="restaurant_name" class="form-label">Restaurant Name</label>
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
                        @foreach ($allCategories as $category)
                            <div class="col-md-4 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cuisine_types[]"
                                        value="{{ $category->id }}" id="cuisine-{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }}>
                                    <label class="form-check-label text-navy" for="cuisine-{{ $category->id }}">
                                        {{ $category->categories_name }}
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
                        <label for="phone_number" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            value="{{ $restaurant->phone_number }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ Auth::user()->email }}" readonly>
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

            {{-- 3. 営業時間セクション --}}
            <div class="profile-card">
                <div class="section-title">Operating Hours</div>

                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    @php
                        $dayData = $restaurant->operating_hours[$day] ?? ($restaurant->hours[$day] ?? []);
                        $dayLower = strtolower($day);
                        $isClosed = isset($dayData['closed']) && $dayData['closed'] == '1';

                        $shifts = [];
                        foreach ($dayData as $key => $val) {
                            if (is_numeric($key)) {
                                $shifts[$key] = $val;
                            }
                        }

                        if (empty($shifts)) {
                            if (isset($dayData['open'])) {
                                $shifts[0] = ['open' => $dayData['open'], 'close' => $dayData['close'] ?? ''];
                            } else {
                                $shifts[0] = ['open' => '', 'close' => ''];
                            }
                        }
                    @endphp
                    <div class="row align-items-start mb-4">
                        <div class="col-md-2 col-12 mb-2 mb-md-0 pt-2">
                            <span class="fw-bold text-navy">{{ $day }}</span>
                        </div>

                        <div class="col-md-8 col-8">
                            <div class="time-inputs-container" id="{{ $dayLower }}-time-container">
                                
                                @foreach($shifts as $index => $shift)
                                    <div class="time-input-row d-flex align-items-center gap-3 mb-2">
                                        <div class="col-auto" style="min-width: 55px;">
                                            <span class="text-muted small shift-label">Shift {{ $index + 1 }}</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="time-picker-wrapper">
                                                <input type="time" class="form-control time-input"
                                                    name="hours[{{ $day }}][{{ $index }}][open]"
                                                    value="{{ $shift['open'] ?? '' }}" {{ $isClosed ? 'disabled' : '' }}>
                                                <i class="fa-regular fa-clock clock-icon"></i>
                                            </div>
                                        </div>
                                        <div class="text-muted small">to</div>
                                        <div class="flex-grow-1">
                                            <div class="time-picker-wrapper">
                                                <input type="time" class="form-control time-input"
                                                    name="hours[{{ $day }}][{{ $index }}][close]"
                                                    value="{{ $shift['close'] ?? '' }}" {{ $isClosed ? 'disabled' : '' }}>
                                                <i class="fa-regular fa-clock clock-icon"></i>
                                            </div>
                                        </div>
                                        <button type="button" class="remove-shift-btn" title="Remove shift">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                @endforeach

                            </div>

                            <div class="mt-2">
                                <button type="button" class="add-shift-btn" data-day="{{ $dayLower }}"
                                    data-day-name="{{ $day }}" {{ $isClosed ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-plus"></i> Add break / second shift
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2 col-4 text-end pt-2">
                            <div class="form-check d-inline-block">
                                <input class="form-check-input closed-switch" type="checkbox"
                                    name="hours[{{ $day }}][closed]" value="1"
                                    id="closed-{{ $day }}"
                                    {{ $isClosed ? 'checked' : '' }}>
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
                    <select name="stay_duration" class="form-select">
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
                    @foreach ($allFeatures as $feature)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-navy">{{ $feature->features_name }}</span>
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" name="features[]"
                                    value="{{ $feature->id }}" id="feature-{{ $feature->id }}"
                                    {{ in_array($feature->id, $selectedFeatureIds) ? 'checked' : '' }}>
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

    {{-- 💡 削除確認用のBootstrapモーダルを追加 --}}
    <div class="modal fade" id="deleteShiftModal" tabindex="-1" aria-labelledby="deleteShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-body p-4 text-center">
                    <div class="text-danger mb-3">
                        <i class="fa-solid fa-circle-exclamation fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-navy mb-2" id="deleteShiftModalLabel">Are you sure?</h5>
                    <p class="text-muted small mb-4">Do you really want to delete this shift slot? This action cannot be undone.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger fw-semibold px-4 py-2" style="border-radius: 8px;">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ⚙️ 制御用JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // モーダルインスタンスの生成と保持変数
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteShiftModal'));
            let activeRowToDelete = null; // 削除対象の一時格納用

            // 時計アイコンをクリックしたら標準ピッカーを開く
            document.body.addEventListener('click', function(e) {
                const clockIcon = e.target.closest('.clock-icon');
                if (clockIcon) {
                    const wrapper = clockIcon.closest('.time-picker-wrapper');
                    const timeInput = wrapper.querySelector('.time-input');
                    if (timeInput) {
                        timeInput.showPicker();
                    }
                }
            });

            // 1. シフト追加ボタン
            document.querySelectorAll('.add-shift-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const dayKey = this.getAttribute('data-day');
                    const dayName = this.getAttribute('data-day-name');
                    const container = document.getElementById(`${dayKey}-time-container`);

                    const currentRows = container.querySelectorAll('.time-input-row').length;
                    const nextIndex = currentRows; 
                    const shiftNumber = currentRows + 1; 

                    const newRow = document.createElement('div');
                    newRow.className = 'time-input-row d-flex align-items-center gap-3 mb-2';
                    newRow.innerHTML = `
                        <div class="col-auto" style="min-width: 55px;">
                            <span class="text-muted small shift-label">Shift ${shiftNumber}</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="time-picker-wrapper">
                                <input type="time" class="form-control time-input" name="hours[${dayName}][${nextIndex}][open]">
                                <i class="fa-regular fa-clock clock-icon"></i>
                            </div>
                        </div>
                        <div class="text-muted small">to</div>
                        <div class="flex-grow-1">
                            <div class="time-picker-wrapper">
                                <input type="time" class="form-control time-input" name="hours[${dayName}][${nextIndex}][close]">
                                <i class="fa-regular fa-clock clock-icon"></i>
                            </div>
                        </div>
                        <button type="button" class="remove-shift-btn" title="Remove shift">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    `;

                    container.appendChild(newRow);
                });
            });

            // ナンバー・インデックス再整列処理
            function reindexShifts(container, dayName) {
                container.querySelectorAll('.time-input-row').forEach((row, index) => {
                    const label = row.querySelector('.shift-label');
                    if (label) {
                        label.textContent = `Shift ${index + 1}`;
                    }

                    const openInput = row.querySelector('input[name$="[open]"]');
                    const closeInput = row.querySelector('input[name$="[close]"]');

                    if (openInput) {
                        openInput.setAttribute('name', `hours[${dayName}][${index}][open]`);
                    }
                    if (closeInput) {
                        closeInput.setAttribute('name', `hours[${dayName}][${index}][close]`);
                    }
                });
            }

            // 2. ゴミ箱ボタンクリック（モーダル呼び出しを挟む）
            document.body.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-shift-btn');
                if (removeBtn) {
                    const row = removeBtn.closest('.time-input-row');
                    const container = row.closest('.time-inputs-container');
                    const allRows = container.querySelectorAll('.time-input-row');

                    // シフトが2つ以上ある場合のみ、削除確認モーダルを表示させる
                    if (allRows.length > 1) {
                        activeRowToDelete = row; // 削除対象を一時退避
                        deleteModal.show();      // モーダルオープン
                    } else {
                        // 最後の1つの場合はモーダルを出さず、値を空にするクリア処理に留める
                        row.querySelectorAll('input[type="time"]').forEach(input => input.value = '');
                    }
                }
            });

            // 3. モーダル内の「Delete」確定ボタンが押された時の実行ロジック
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (activeRowToDelete) {
                    const row = activeRowToDelete;
                    const container = row.closest('.time-inputs-container');
                    const nameAttr = row.querySelector('input[name^="hours["]').getAttribute('name');
                    const dayName = nameAttr.split('][')[0].replace('hours[', '');

                    row.remove();
                    reindexShifts(container, dayName);

                    activeRowToDelete = null; // リセット
                    deleteModal.hide();       // モーダルを閉じる
                }
            });

            // 4. Closed（定休日）切り替えの活性・非活性制御
            document.body.addEventListener('change', function(e) {
                const closedSwitch = e.target.closest('.closed-switch');
                if (closedSwitch) {
                    const rowContainer = closedSwitch.closest('.row');
                    const timeContainer = rowContainer.querySelector('.time-inputs-container');
                    const addBtn = rowContainer.querySelector('.add-shift-btn');
                    const isChecked = closedSwitch.checked;

                    timeContainer.querySelectorAll('input[type="time"]').forEach(input => {
                        input.disabled = isChecked;
                        if (isChecked) input.value = '';
                    });

                    if (addBtn) addBtn.disabled = isChecked;
                }
            });
        });
    </script>
@endsection