<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogController extends Controller
{
    public function store(Request $request)
    {
        $ip = $request->ip();

        // ۱. استخراج اطلاعات شبکه (ISP / VPN)
        $network = $this->getNetworkDetails($ip);

        // ۲. ثبت در دیتابیس
        DB::table('home_logs')->insert([
            'fingerprint'  => $request->input('fingerprint'), // شناسایی از روی UUID ذخیره شده
            'ip_address'   => $ip,
            'isp'          => $network['isp'],
            'country'      => $network['country'],
            'city'         => $network['city'],
            'is_vpn'       => $network['is_vpn'],
            'browser'      => $this->parseBrowser($request->userAgent()),
            'platform'     => $this->parsePlatform($request->userAgent()),
            'gpu'          => $request->input('gpu'),
            'battery'      => $request->input('battery'),
            'cpu_threads'  => $request->input('cpu_threads'),
            'screen_res'   => $request->input('screen_res'),
            'event_type'   => $request->input('event_type', 'visit'),
            'target_name'  => $request->input('target_name'),
            'url'          => $request->input('url'),
            'referer'      => $request->input('referer'),
            'user_agent'   => $request->userAgent(),
            'payload'      => json_encode($request->all()),
            'created_at'   => now(),
        ]);

        return response()->json(['status' => 'Captured']);
    }

    private function getNetworkDetails($ip) {
        try {
            // استفاده از سرویس ip-api برای تشخیص VPN و ISP
            $res = Http::get("http://ip-api.com/json/{$ip}?fields=status,country,city,isp,proxy,hosting")->json();
            if ($res['status'] === 'success') {
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
        if (Str::contains($ua, 'Edge')) return 'Edge';
        return 'Unknown';
    }

    private function parsePlatform($ua) {
        if (Str::contains($ua, 'Windows')) return 'Windows';
        if (Str::contains($ua, 'Android')) return 'Android';
        if (Str::contains($ua, 'iPhone')) return 'iOS';
        if (Str::contains($ua, 'Macintosh')) return 'MacOS';
        return 'Linux';
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
