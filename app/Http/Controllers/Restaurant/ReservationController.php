<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function dashboard()
    {

        $restaurant = auth()->user()->restaurant;

        if (!$restaurant) {
            abort(403);
        }

        if ($restaurant->status !== 'approved') {
            abort(403, 'Your restaurant has not been approved yet.');
        }

        return view('restaurants.index', compact('restaurant'));
    }

    public function index(Request $request)
    {
        $restaurantId = 1;

        $query = Reservation::where('restaurant_id', $restaurantId)
            ->with('user')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc');

        //  1. 日付での絞り込みオブジェクト（既存の処理）
        if ($request->filled('date')) {
            $selectedDate = $request->input('date');
            $query->whereDate('reservation_date', $selectedDate);
        } else {
            $selectedDate = null;
        }

        //  2. 予約番号での検索オブジェクト
        if ($request->filled('search_id')) {
            $searchKeyword = $request->input('search_id');

            // 「RM003」や「#RM003」から数字の「3」だけを抽出するオブジェクト
            $cleanId = preg_replace('/[^0-9]/', '', $searchKeyword);

            if (!empty($cleanId)) {
                $query->where('id', $cleanId);
            }
        }

        $reservations = $query->get();

        // 各ステータスオブジェクトごとの分配
        $pendingReservations   = $reservations->where('status', 'pending');
        $confirmedReservations = $reservations->where('status', 'confirmed');
        $completedReservations = $reservations->where('status', 'completed');
        $cancelledReservations = $reservations->where('status', 'cancelled');

        return view('restaurants.reservations.index', compact(
            'reservations',
            'pendingReservations',
            'confirmedReservations',
            'completedReservations',
            'cancelledReservations',
            'selectedDate'
        ));
    }

    //  予約ステータスを更新するアクションオブジェクト
    public function updateStatus(Request $request, Reservation $reservation)
    {
        // 送られてきたステータスオブジェクトをバリデーション
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        //  誰がキャンセルしたかの判定ロジックオブジェクトを追加
        if ($validated['status'] === 'cancelled') {
            // 店舗側のコントローラーを通るアクションはすべて店舗によるものなので 'restaurant' をセット
            $reservation->cancelled_by = 'restaurant';
        } else {
            // もし確定や来店完了に書き換える場合は、以前のキャンセル記録オブジェクトをクリア
            $reservation->cancelled_by = null;
        }

        // ステータスオブジェクトを更新して保存
        $reservation->status = $validated['status'];
        $reservation->save();

        return redirect()->back()->with('success', 'Reservation status updated successfully.');
    }
}
