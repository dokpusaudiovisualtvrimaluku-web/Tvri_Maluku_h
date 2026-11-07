<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dashboardData = DashboardMetricsService::getMetrics($request);

        return view('executive.dashboard', $dashboardData);
    }
}
