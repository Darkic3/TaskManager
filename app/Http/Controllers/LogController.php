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
        $logId = session('last_visit_id');

        if (!$logId) {
            return response()->json(['status' => 'No SessionFound'], 400);
        }

        $ua = $request->userAgent();
        $ip = $request->ip();

        $network = $this->getNetworkDetails($ip);

        DB::table('home_logs')->where('id', $logId)->update([
            'fingerprint'  => $request->input('fingerprint'),
            'isp'          => $network['isp'],
            'country'      => $network['country'],
            'city'         => $network['city'],
            'is_vpn'       => $network['is_vpn'],
            'browser'      => $this->parseBrowser($ua),
            'platform'     => $this->parsePlatform($ua),
            'gpu'          => $request->input('gpu'),
            'battery'      => $request->input('battery'),
            'cpu_threads'  => (int) $request->input('cpu_threads'),
            'screen_res'   => $request->input('screen_res'),
            'browser_lang' => $request->input('browser_lang'),
            'timezone'     => $request->input('timezone'),
            'payload'      => json_encode($request->all()),
            'updated_at'   => now(),
        ]);

        return response()->json(['status' => 'Human Verified & Data Captured']);
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
