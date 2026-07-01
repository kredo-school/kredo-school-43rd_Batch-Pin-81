<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->with('replies')
            ->get();

        return view('customer.contact', compact('contacts'));
    }
    public function send(Request $request)
    {
        // 👑 バリデーションに parent_id の存在チェックを追加して堅牢化
        $request->validate([
            'message' => 'required|string',
            'parent_id' => 'nullable|exists:contacts,id',
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // メッセージの保存
        $contact = new Contact();
        $contact->user_id = Auth::id();
        $contact->message = $request->message;

        // 👑 input() を使い、親IDがあればセット、なければnull
        $contact->parent_id = $request->input('parent_id');

        // 📸 画像のアップロード処理
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $paths[] = $path;
            }
        }

        $contact->attachments = !empty($paths) ? $paths : null;
        $contact->save();

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function search()
    {
        return view('contact.index');
    }

    public function destroy($id)
    {
        // 👑 引数を $id に変更し、確実にレコードを取得（なければ404）
        $contact = Contact::findOrFail($id);

        // 所有者チェック（他人の問い合わせを削除できないように防衛）
        if ($contact->user_id !== auth()->id()) {
            abort(403);
        }

        // 📁 添付ファイルがある場合はストレージからも物理削除
        if (!empty($contact->attachments)) {
            // 💡 過去の古いデータ（文字列）が来ても、エラーにならないよう配列に安全変換
            if (is_array($contact->attachments)) {
                $attachments = $contact->attachments;
            } else {
                $decoded = json_decode($contact->attachments, true);
                $attachments = is_array($decoded) ? $decoded : [$contact->attachments];
            }foreach ($attachments as $path) {
                if (!empty($path) && is_string($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $contact->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
