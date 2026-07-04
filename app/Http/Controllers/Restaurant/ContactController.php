<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    //一覧・問い合わせ画面表示
    public function index()
    {
        $contacts = Contact::with('replies')
            ->where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('restaurants.settings.contact', compact('contacts'));
    }

    //新規送信処理
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $contact = new Contact();
        $contact->user_id = Auth::id(); 
        $contact->message = $request->message;
        $contact->parent_id = null; 
        $contact->status = 'open';

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('attachments', 'public'); 
            }
        }
        $contact->attachments = !empty($paths) ? $paths : null;
        $contact->save();

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

     //返信（フォローアップ）送信処理
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $parentContact = Contact::findOrFail($id);

        $reply = new Contact();
        $reply->user_id = Auth::id();
        $reply->message = $request->message;
        $reply->parent_id = $parentContact->id;
        $reply->status = 'open';

        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('attachments', 'public'); 
            }
        }
        $reply->attachments = !empty($paths) ? $paths : null;
        $reply->save();

        return redirect()->back()->with('success', 'Follow-up message sent successfully!');
    }

    //削除処理（過去の古い形式のデータが来ても絶対にクラッシュしない安全版）
    public function destroy($id)
    {
        $contact = Contact::where('user_id', Auth::id())->findOrFail($id);

        // 子メッセージのループ削除
        foreach ($contact->replies as $reply) {
            $this->deletePhysicalFiles($reply->attachments);
        }

        // 親メッセージのループ削除
        $this->deletePhysicalFiles($contact->attachments);

        Contact::where('parent_id', $id)->delete();
        $contact->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }

     //📁 古いデータが混ざっても絶対に落ちない安全な削除ヘルパー
    private function deletePhysicalFiles($attachmentsData)
    {
        if (empty($attachmentsData)) return;

        // 配列か文字列（JSON）かを自動判定して安全に1つの配列にする魔法の処理
        if (is_array($attachmentsData)) {
            $attachments = $attachmentsData;
        } else {
            $decoded = json_decode($attachmentsData, true);
            $attachments = is_array($decoded) ? $decoded : [$attachmentsData];
        }

        foreach ($attachments as $path) {
            if (!empty($path) && is_string($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}