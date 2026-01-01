<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LogController extends Controller
{
    public function store(Request $request)
    {
        $logId = session('last_log_id');

        if (!$logId) return response()->json(['error' => 'Session expired'], 400);

        // دریافت اطلاعات ISP و مکان (در صورت نیاز)
        $res = \Http::get("http://ip-api.com/json/{$request->ip()}?fields=status,country,city,isp,proxy,hosting")->json();

        \DB::table('home_logs')->where('id', $logId)->update([
            'fingerprint'  => $request->fingerprint,
            'gpu'          => $request->gpu,
            'screen_res'   => $request->screen_res,
            'cpu_threads'  => (int)$request->cpu_threads,
            'battery'      => $request->battery,
            'timezone'     => $request->timezone,
            'browser_lang' => $request->browser_lang,
            'isp'          => $res['isp'] ?? 'Unknown',
            'country'      => $res['country'] ?? 'Unknown',
            'city'         => $res['city'] ?? 'Unknown',
            'is_vpn'       => (isset($res['proxy']) && ($res['proxy'] || $res['hosting'])) ? 1 : 0,
            'event_type'   => $request->event_type ?? 'visit',
            'target_name'  => $request->target_name ?? null,
            'updated_at'   => now(),
        ]);

        return response()->json(['status' => 'Human Verified']);
    }
    private function getNetworkDetails($ip) {
        try {
            $res = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=status,country,city,isp,proxy,hosting")->json();
            if (isset($res['status']) && $res['status'] === 'success') {
                return [
                    'isp'     => $res['isp'],
                    'country' => $res['country'],
                    'city'    => $res['city'],
                    'is_vpn'  => ($res['proxy'] || $res['hosting']) ? 1 : 0
                ];
            }
        } catch (\Exception $e) {}
        return ['isp' => 'Unknown', 'country' => 'Unknown', 'city' => 'Unknown', 'is_vpn' => 0];
    }

    private function parseBrowser($ua) {
        if (Str::contains($ua, 'Chrome')) return 'Chrome';
        if (Str::contains($ua, 'Firefox')) return 'Firefox';
        if (Str::contains($ua, 'Safari')) return 'Safari';
        return 'Other';
    }

    private function parsePlatform($ua) {
        if (Str::contains($ua, 'Windows')) return 'Windows';
        if (Str::contains($ua, 'Android')) return 'Android';
        if (Str::contains($ua, 'iPhone')) return 'iOS';
        return 'Other';
    }
}
