<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $ua = $request->userAgent();

            $cpu = $request->cpu_threads;
            $cpu_numeric = is_numeric($cpu) ? intval($cpu) : null;

            DB::table('home_logs')->insert([
                'user_id'      => auth()->id(),
                'event_type'   => $request->event_type ?? 'info_update',
                'target_name'  => $request->target_name,
                'ip_address'   => $request->ip(),
                'user_agent'   => $ua,
                'referer'      => $request->referer,
                'url'          => $request->url,
                'method'       => $request->method() ?? 'POST',
                'browser'      => $this->getBrowser($ua),
                'platform'     => $this->getPlatform($ua),
                'screen_res'   => $request->screen_res,
                'timezone'     => $request->timezone,
                'gpu'          => $request->gpu,
                'cpu_threads'  => $cpu_numeric,
                'battery'      => $request->battery,
                'browser_lang' => $request->browser_lang,
                'payload'      => json_encode($request->all()),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error("Log Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getBrowser($ua) {
        if (Str::contains($ua, 'Chrome')) return 'Chrome';
        if (Str::contains($ua, 'Firefox')) return 'Firefox';
        if (Str::contains($ua, 'Safari')) return 'Safari';
        return 'Unknown';
    }

    private function getPlatform($ua) {
        if (Str::contains($ua, 'Windows')) return 'Windows';
        if (Str::contains($ua, 'Android')) return 'Android';
        if (Str::contains($ua, 'iPhone')) return 'iOS';
        if (Str::contains($ua, 'Macintosh')) return 'MacOS';
        return 'Linux';
    }
}
