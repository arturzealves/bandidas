<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        return view(sprintf('dashboard/%s', $user->userType->name))
            ->with([
                'user' => $user,
            ]
        );
    }
}
