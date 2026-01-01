<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HomeMiddleWare
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            $logId = DB::table('home_logs')->insertGetId([
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'method'       => $request->method(),
                'url'          => $request->fullUrl(),
                'referer'      => $request->header('referer'),
                'language'     => $request->header('accept-language'),
                'event_type'   => 'visit',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            session(['last_visit_id' => $logId]);
        }

        return $next($request);
    }
}
