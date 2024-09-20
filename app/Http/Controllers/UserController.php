<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user('role'); // Get the authenticated user
        // dd($user->role);

        return view('user.show', compact('user')); // Pass user data to the view
    }
}
