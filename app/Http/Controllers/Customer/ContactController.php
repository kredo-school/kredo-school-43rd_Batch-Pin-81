<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // バリデーション
    $request->validate([
        'message' => 'required|string',
        'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // 画像のみ許可
    ]);

    // メッセージの保存
    $contact = new Contact();
    $contact->user_id = Auth::id();
    $contact->message = $request->message;
    
    // 💡 返信（子メッセージ）の場合は parent_id をセット
    if ($request->has('parent_id')) {
        $contact->parent_id = $request->parent_id;
    }

    // 📸 画像のアップロード処理
    $paths = [];
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            // storage/app/public/attachments に一意の名前で保存
            $path = $file->store('attachments', 'public'); 
            $paths[] = $path; // 例: "attachments/abcdef12345.png" が入る
        }
    }

    // 配列をJSON形式に変換して保存（またはModel側で $casts を設定）
    $contact->attachments = !empty($paths) ? $paths : null;
    $contact->save();

    return redirect()->back()->with('success', 'Message sent successfully!');
}

    public function search()
    {
        return view('contact.index');
    }
}