<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PromoterController extends UserController
{
    public function show(User $promoter)
    {
        return view('promoters.show')->with('promoter', $promoter);
    }
}
