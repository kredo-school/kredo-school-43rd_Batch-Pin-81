<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function myPage()
    {
        $user = Auth::user();

        return view('customer.my_page', compact('user'));
    }
}
