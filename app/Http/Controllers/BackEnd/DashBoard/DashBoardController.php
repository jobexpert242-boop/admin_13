<?php

namespace App\Http\Controllers\BackEnd\DashBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashBoardController extends Controller
{
    public function dashBoard()
    {
        return Inertia::render('BackEnd/DashBoard/DashBoard');
    }
}
