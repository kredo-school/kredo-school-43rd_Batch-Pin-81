<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('customer.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Your message was sent successfully.');
    }

    public function search()
    {
        return view('contact.index');
    }
}
