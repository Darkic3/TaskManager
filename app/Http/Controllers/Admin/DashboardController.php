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
        $fileName = 'advanced_logs_' . now()->format('Y-m-d_H-i') . '.csv';

        // استفاده از Cursor برای مدیریت حافظه در صورتی که تعداد لاگ‌ها زیاد باشد
        $logs = HomeLog::orderBy('created_at', 'desc')->cursor();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // تمام ستون‌های موجود در جدول برای تحلیل دقیق
        $columns = [
            'ID', 'Fingerprint', 'IP Address', 'ISP', 'Country', 'City', 'Is VPN',
            'Event Type', 'Target Name', 'Browser', 'Platform', 'Device', 'Language',
            'Browser Lang', 'Screen Res', 'GPU', 'CPU Threads', 'Battery', 'Timezone',
            'URL', 'Referer', 'Method', 'User ID', 'User Agent', 'Created At'
        ];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');

            // اضافه کردن BOM برای نمایش صحیح حروف در Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // نوشتن سرتیترها
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->fingerprint, // کلید اصلی شناسایی فرد
                    $log->ip_address,
                    $log->isp,
                    $log->country,
                    $log->city,
                    $log->is_vpn ? 'Yes' : 'No',
                    $log->event_type,
                    $log->target_name,
                    $log->browser,
                    $log->platform,
                    $log->device,
                    $log->language,
                    $log->browser_lang,
                    $log->screen_res,
                    $log->gpu,
                    $log->cpu_threads,
                    $log->battery,
                    $log->timezone,
                    $log->url,
                    $log->referer,
                    $log->method,
                    $log->user_id,
                    $log->user_agent,
                    $log->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
