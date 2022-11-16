<?php

namespace App\Http\Controllers;

use App\Models\Promoter;
use App\Models\User;
use Illuminate\Http\Request;

class PromoterController extends Controller
{
    public function show(Promoter $promoter)
    {
        return view('promoters.show')->with('promoter', $promoter);
    }
}
