<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// ※実際のモデル名に合わせて適宜変更する（例: ContactMessage, Reply など）
// use App\Models\ContactMessage; 

class ContactController extends Controller
{
    /**
     * メッセージ履歴一覧画面 (Message History)
     */
    public function index()
    {
        // 実際の開発ではデータベースからログインユーザーのメッセージ一覧を取得
        // $messages = ContactMessage::where('user_id', auth()->id())->latest()->get();
        
        // 【修正点】ダミーデータをオブジェクト型（Collection）に変換して、将来のDB連携と同じ動きにします
        $messages = collect([
            (object)[
                'id' => 1,
                'name' => 'John Doe', // ブレード側で呼んでいるnameを追加しておきました
                'subject' => 'Subscription inquiry',
                'created_at' => '2026-05-10 09:30',
                'status' => 'replied'
            ],
            (object)[
                'id' => 2,
                'name' => 'Jane Smith',
                'subject' => 'Dashboard access issue',
                'created_at' => '2026-05-08 11:15',
                'status' => 'replied'
            ],
        ]);

        return view('restaurants.settings.contact', compact('messages'));
    }

    /**
     * メッセージ詳細画面 (各件名のスレッド表示)
     */
    public function show($id)
    {
        // データベースから該当するメッセージと、そのやり取り（返信履歴）を取得
        // $message = ContactMessage::with('replies')->findOrFail($id);

        // ダミーデータ例（ID: 1 の場合）
        $message = (object)[
            'id' => $id,
            'subject' => $id == 1 ? 'Subscription inquiry' : 'Dashboard access issue',
            'replies' => [
                [
                    'sender' => 'user',
                    'content' => $id == 1 ? 'I would like to know more about upgrading our plan.' : 'We are unable to access our dashboard since this morning.',
                    'created_at' => $id == 1 ? '2026-05-10 09:30' : '2026-05-08 11:15',
                ],
                [
                    'sender' => 'support',
                    'content' => $id == 1 ? 'Thank you for reaching out! We offer premium plans starting at ¥9,800/month. Please check your email for the full pricing breakdown.' : 'Our team has resolved the access issue. Please try logging in again and let us know if the problem persists.',
                    'created_at' => $id == 1 ? '2026-05-10 14:20' : '2026-05-08 12:00',
                ],
            ]
        ];

        return view('restaurant.settings.contact_show', compact('message'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255', 
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Your message was sent successfully');
    }

    public function sendFollowUp(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // 該当メッセージに対する返信（追記）をデータベースに保存する処理
        // Reply::create([...]);

        return back()->with('success', 'Follow-up message sent successfully');
    }
}