<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IC3 Control Center | Ultimate Tracking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', sans-serif; background-color: #010409; color: #f0fdff; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .cyber-border { border: 1px solid rgba(0, 243, 255, 0.15); }
        .neon-text { text-shadow: 0 0 12px rgba(0, 243, 255, 0.4); }
        .vpn-alert { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.4); color: #ef4444; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #00f3ff; border-radius: 10px; }
    </style>
</head>
<body class="p-4 md:p-8">

<div class="max-w-[1700px] mx-auto">
    <header class="mb-10 flex flex-col md:flex-row justify-between items-center border-b border-cyan-900/50 pb-6">
        <div>
            <h1 class="text-4xl font-bold neon-text uppercase tracking-widest text-cyan-400">System Logs <span class="text-xs font-mono text-cyan-600 font-light">v1.2 [Full-Audit]</span></h1>
            <p class="text-gray-500 text-xs mt-2 mono uppercase">Database: home_logs | Total Records: {{ $logs->total() }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="px-4 py-2 rounded border border-cyan-500/30 text-cyan-400 text-[10px] mono">SECURE_ACCESS_GRANTED</span>
        </div>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 text-center">
        <div class="cyber-border bg-gray-900/40 p-5 rounded-xl">
            <p class="text-gray-500 text-[10px] mb-1">VISITS</p>
            <h3 class="text-3xl font-bold mono text-cyan-400">{{ $totalVisits }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/40 p-5 rounded-xl border-pink-500/20">
            <p class="text-gray-500 text-[10px] mb-1">CLICKS</p>
            <h3 class="text-3xl font-bold mono text-pink-500">{{ $totalClicks }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/40 p-5 rounded-xl border-yellow-500/20">
            <p class="text-gray-500 text-[10px] mb-1">TOP BROWSER</p>
            <h3 class="text-lg font-bold mono truncate">{{ $topBrowser->browser ?? 'N/A' }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/40 p-5 rounded-xl border-green-500/20">
            <p class="text-gray-500 text-[10px] mb-1">UPTIME</p>
            <h3 class="text-lg font-bold mono">100%</h3>
        </div>
    </div>
    <a href="{{ route('admin.export') }}" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded flex items-center gap-2 transition-all shadow-lg shadow-green-900/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        DOWNLOAD CSV REPORT
    </a>

    <div class="cyber-border bg-gray-900/20 rounded-2xl overflow-hidden backdrop-blur-sm shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse whitespace-nowrap">
                <thead>
                <tr class="bg-cyan-950/40 text-cyan-400 text-[11px] uppercase tracking-wider border-b border-cyan-900/50">
                    <th class="p-4">Time & Identity</th>
                    <th class="p-4">Connectivity (ISP/IP)</th>
                    <th class="p-4">Geo Location</th>
                    <th class="p-4">Hardware Profile</th>
                    <th class="p-4">Environment & Session</th>
                    <th class="p-4">Interaction</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50 text-gray-300">
                @foreach($logs as $log)
                    @php
                        // تبدیل پی‌لود به آبجکت در صورتی که مدل کستینگ ندارد
                        $payload = is_string($log->payload) ? json_decode($log->payload) : (object)$log->payload;
                    @endphp
                    <tr class="hover:bg-cyan-900/10 transition-all group">
                        <td class="p-4">
                            <span class="block text-[10px] text-gray-500 mono mb-1">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s Y/m/d') }}
                            </span>
                            <div class="flex flex-col gap-1">
                                <span class="text-cyan-400 font-bold mono text-[10px] tracking-tighter">
                                    {{ $log->fingerprint ?? 'NO_FINGERPRINT' }}
                                </span>
                                @if($log->user_id)
                                    <span class="text-[9px] text-yellow-500 font-bold">UID: {{ $log->user_id }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="mono text-sm font-bold text-gray-100">{{ $log->ip_address }}</span>
                                @if($log->is_vpn)
                                    <span class="vpn-alert text-[8px] px-1.5 py-0.5 rounded uppercase font-bold">VPN</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-cyan-700 block max-w-[180px] truncate" title="{{ $log->isp }}">
                                ISP: {{ $log->isp ?? 'Unknown' }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-200">{{ $log->country ?? 'Unknown' }}</span>
                                <span class="text-[10px] text-gray-500 italic">{{ $log->city ?? 'N/A' }}</span>
                                <span class="text-[9px] text-gray-600 mono">{{ $log->timezone }}</span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="text-[10px] space-y-1">
                                <p><span class="text-cyan-600 uppercase">GPU:</span> <span class="text-gray-400">{{ Str::limit($log->gpu, 20) }}</span></p>
                                <p><span class="text-cyan-600 uppercase">CPU:</span> <span class="text-gray-400">{{ $log->cpu_threads }} Cores</span></p>
                                <p><span class="text-cyan-600 uppercase">BAT:</span> <span class="{{ Str::contains($log->battery, 'Discharging') ? 'text-orange-500' : 'text-green-500' }}">{{ $log->battery }}</span></p>
                                <p><span class="text-cyan-600 uppercase">RES:</span> <span class="text-gray-400">{{ $log->screen_res }}</span></p>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex gap-1">
                                    <span class="text-[9px] bg-gray-800 px-1.5 py-0.5 rounded text-gray-400">{{ $log->platform }}</span>
                                    <span class="text-[9px] bg-gray-800 px-1.5 py-0.5 rounded text-gray-400">{{ $log->browser }}</span>
                                </div>
                                <span class="text-[9px] text-gray-500 mono truncate max-w-[150px]" title="{{ $log->user_agent }}">
                                    UA: {{ Str::limit($log->user_agent, 40) }}
                                </span>
                                <span class="text-[9px] text-cyan-800">Lang: {{ $log->language }} | {{ $log->browser_lang }}</span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col items-end gap-1">
                                <div class="flex gap-1">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $log->event_type == 'click' ? 'bg-pink-500/20 text-pink-400 border border-pink-500/30' : 'bg-green-500/20 text-green-400 border border-green-500/30' }}">
                                        {{ strtoupper($log->event_type) }}
                                    </span>
                                    <span class="bg-blue-900/30 text-blue-400 text-[9px] px-1.5 py-0.5 rounded border border-blue-500/20 uppercase">{{ $log->method }}</span>
                                </div>
                                <span class="text-[10px] text-cyan-300 font-bold uppercase">{{ $log->target_name ?? 'Page View' }}</span>
                                <span class="text-[8px] text-gray-600 mono truncate max-w-[120px]" title="{{ $log->url }}">URL: {{ $log->url }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-between items-center bg-gray-900/20 p-4 rounded-xl cyber-border gap-4">
        <div class="text-[10px] text-gray-500 mono uppercase">
            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} audit_entries
        </div>
        <div class="mono">
            {{ $logs->links() }}
        </div>
    </div>
</div>

</body>
</html>
