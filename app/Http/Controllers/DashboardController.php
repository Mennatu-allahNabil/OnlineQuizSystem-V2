<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count()-1;
        $totalTakers = User::where("role","user")->count();
        $totalAdmins =User::where("role","admin")->count();
        $recentQuizzes = Quiz::latest()->take(5)->get();
        return view('Dashboard.index', compact("totalUsers",'totalTakers',"totalAdmins","recentQuizzes"));
    }
}
