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

    public function export()
    {
        $fileName = 'logs_' . now()->format('Y-m-d') . '.csv';
        $logs = \App\Models\HomeLog::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'IP', 'ISP', 'Country', 'City', 'Browser', 'OS', 'GPU', 'Battery', 'Event', 'Target', 'Time'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->ip_address,
                    $log->isp,
                    $log->country,
                    $log->city,
                    $log->browser,
                    $log->platform,
                    $log->gpu,
                    $log->battery,
                    $log->event_type,
                    $log->target_name,
                    $log->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
