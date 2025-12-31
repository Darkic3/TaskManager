<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HomeMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            'user_id'    => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url'        => $request->fullUrl(),
            'referer'    => $request->header('referer'),
            'method'     => $request->method(),
            'language'   => $request->header('accept-language'),
            'payload'    => json_encode($request->except(['password', 'password_confirmation'])),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        try {
            DB::table('home_logs')->insert($data);
        } catch (\Exception $e) {
            report($e);
        }

        return $next($request);
    }
}
