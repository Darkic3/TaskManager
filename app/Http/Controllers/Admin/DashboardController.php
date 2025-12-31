<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $totalVisits = HomeLog::where('event_type', 'visit')->count();
        $totalClicks = HomeLog::where('event_type', 'click')->count();
        $topBrowser = HomeLog::select('browser', \DB::raw('count(*) as total'))
            ->groupBy('browser')->orderBy('total', 'desc')->first();

        $logs = HomeLog::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.dashboard', compact('logs', 'totalVisits', 'totalClicks', 'topBrowser'));
    }
}
