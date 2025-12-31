<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LogController extends Controller
{
    public function store(Request $request)
    {
        $ua = $request->input('user_agent', $request->userAgent());

        $browser = $this->parseBrowser($ua);
        $platform = $this->parsePlatform($ua);

        DB::table('home_logs')->insert([
            'user_id'      => auth()->id(),
            'browser'      => $browser,
            'platform'     => $platform,
            'device'       => $request->header('sec-ch-ua-platform') ?? 'Unknown',
            'url'          => $request->input('url'),
            'method'       => $request->method(),
            'language'     => $request->header('accept-language'),
            'event_type'   => $request->input('event_type', 'visit'),
            'target_name'  => $request->input('target_name'),
            'ip_address'   => $request->ip(),
            'user_agent'   => $ua,
            'referer'      => $request->input('referer'),
            'screen_res'   => $request->input('screen_res'),
            'timezone'     => $request->input('timezone'),
            'gpu'          => $request->input('gpu'),
            'cpu_threads'  => (int) $request->input('cpu_threads'),
            'battery'      => $request->input('battery'),
            'browser_lang' => $request->input('browser_lang'),
            'payload'      => json_encode($request->all()), // ذخیره کل اطلاعات برای ردیابی‌های بعدی
            'is_vpn'       => false, // این مورد را می‌توان با APIهایی مثل ip-api.com کامل کرد
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return response()->json(['status' => 'Data Captured']);
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
}            \Log::error("Log Error: " . $e->getMessage());
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
