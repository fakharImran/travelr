<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $pageConfigs = ['pageSidebar' => 'business-overview'];    
        return view('manager.dashboard', compact('pageConfigs'));
    }
}
