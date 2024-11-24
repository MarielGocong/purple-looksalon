<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminDashboardHomeController;
use Illuminate\Http\Request;

class DashboardHomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2) {
            $adminDashboardHomeController = new AdminDashboardHomeController();
            return $adminDashboardHomeController->index();
        } else {
            return redirect()->route('home')->with('error', 'You are not authorized to perform this action.');
        }
    }
}
