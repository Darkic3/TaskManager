<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVisits = DB::table('home_logs')->where('event_type', 'visit')->count();
        $totalClicks = DB::table('home_logs')->where('event_type', 'click')->count();
        $topBrowser = DB::table('home_logs')->select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')->orderBy('total', 'desc')->first();

        $logs = DB::table('home_logs')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.dashboard', compact('logs', 'totalVisits', 'totalClicks', 'topBrowser'));
    }
}
