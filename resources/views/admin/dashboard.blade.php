<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IC3 Control Center | Advanced Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', sans-serif; background-color: #010409; color: #f0fdff; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .cyber-border { border: 1px solid rgba(0, 243, 255, 0.15); }
        .neon-text { text-shadow: 0 0 12px rgba(0, 243, 255, 0.4); }
        .vpn-glow { box-shadow: 0 0 10px rgba(239, 68, 68, 0.3); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #010409; }
        ::-webkit-scrollbar-thumb { background: #00f3ff; border-radius: 10px; }
    </style>
</head>
<body class="p-4 md:p-8">

<div class="max-w-[1600px] mx-auto">
    <header class="mb-10 flex flex-col md:flex-row justify-between items-center border-b border-cyan-900/50 pb-6">
        <div>
            <h1 class="text-4xl font-bold neon-text uppercase tracking-[0.2em] text-cyan-400">System Logs <span class="text-xs font-mono text-cyan-600">v1.1 [Advanced Tracking]</span></h1>
            <p class="text-gray-500 text-xs mt-2 mono">ENCRYPTED DATA FEED ACTIVE...</p>
        </div>
        <div class="mt-4 md:mt-0 text-left">
            <span class="px-3 py-1 rounded-full border border-green-500/30 text-green-500 text-[10px] mono animate-pulse italic">● LIVE RADAR ACTIVE</span>
        </div>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="cyber-border bg-gray-900/40 backdrop-blur-md p-5 rounded-xl">
            <p class="text-cyan-500 text-[10px] uppercase tracking-widest mb-1">Total Signals</p>
            <h3 class="text-3xl font-bold mono">{{ $totalVisits + $totalClicks }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/40 backdrop-blur-md p-5 rounded-xl">
            <p class="text-pink-500 text-[10px] uppercase tracking-widest mb-1">Interaction Rate</p>
            <h3 class="text-3xl font-bold mono text-pink-500">{{ $totalClicks }} <span class="text-xs text-gray-600">clicks</span></h3>
        </div>
        <div class="cyber-border bg-gray-900/40 backdrop-blur-md p-5 rounded-xl">
            <p class="text-yellow-500 text-[10px] uppercase tracking-widest mb-1">Top Access Point</p>
            <h3 class="text-xl font-bold mono truncate">{{ $topBrowser->browser ?? 'N/A' }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/40 backdrop-blur-md p-5 rounded-xl border-red-500/20">
            <p class="text-red-500 text-[10px] uppercase tracking-widest mb-1">Security Status</p>
            <h3 class="text-xl font-bold mono">STABLE</h3>
        </div>
    </div>

    <div class="cyber-border bg-gray-900/20 rounded-2xl overflow-hidden backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                <tr class="bg-cyan-950/40 text-cyan-400 text-[11px] uppercase tracking-tighter border-b border-cyan-900/50">
                    <th class="p-4">Time / UID</th>
                    <th class="p-4">Network & ISP</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Hardware Profile</th>
                    <th class="p-4">Environment</th>
                    <th class="p-4">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50 text-gray-300">
                @foreach($logs as $log)
                    @php $payload = json_decode($log->payload); @endphp
                    <tr class="hover:bg-cyan-900/5 transition-all group">
                        <td class="p-4">
                            <span class="block text-[10px] text-gray-500 mono mb-1">{{ $log->created_at->format('H:i:s Y-m-d') }}</span>
                            <span class="px-2 py-0.5 rounded bg-cyan-900/30 text-cyan-400 text-[9px] mono border border-cyan-500/20 group-hover:border-cyan-400 transition-colors">
                                {{ $payload->fingerprint ?? 'NO-ID' }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <span class="block mono text-sm font-bold text-gray-100">{{ $log->ip_address }}</span>
                                @if($log->is_vpn)
                                    <span class="bg-red-500/20 text-red-500 text-[8px] px-1.5 py-0.5 rounded border border-red-500/30 vpn-glow uppercase">VPN</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-cyan-700 block truncate max-w-[150px]">{{ $log->isp ?? 'Unknown Provider' }}</span>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-200">{{ $log->country ?? 'Unknown' }}</span>
                                <span class="text-[10px] text-gray-500 italic">{{ $log->city ?? 'N/A' }} / {{ $log->timezone }}</span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="text-[10px] leading-relaxed">
                                <span class="text-cyan-500 block">GPU: <span class="text-gray-400">{{ Str::limit($log->gpu, 25) }}</span></span>
                                <span class="text-cyan-500">CPU: <span class="text-gray-400">{{ $log->cpu_threads }} Cores</span></span>
                                <span class="mx-1 text-gray-700">|</span>
                                <span class="text-cyan-500">RES: <span class="text-gray-400">{{ $log->screen_res }}</span></span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] bg-gray-800 px-1.5 py-0.5 rounded text-gray-400">{{ $log->platform }}</span>
                                    <span class="text-[10px] bg-gray-800 px-1.5 py-0.5 rounded text-gray-400">{{ $log->browser }}</span>
                                </div>
                                <span class="text-[10px] {{ Str::contains($log->battery, 'Discharging') ? 'text-yellow-600' : 'text-green-600' }}">
                                    ⚡ {{ $log->battery ?? 'Battery Unknown' }}
                                </span>
                            </div>
                        </td>

                        <td class="p-4">
                            <div class="flex flex-col items-end">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-tighter {{ $log->event_type == 'click' ? 'bg-pink-500/20 text-pink-400 border border-pink-500/30' : 'bg-green-500/20 text-green-400 border border-green-500/30' }}">
                                    {{ strtoupper($log->event_type) }}
                                </span>
                                @if($log->target_name)
                                    <span class="text-[11px] text-cyan-300 font-bold mt-1 uppercase mono tracking-tighter">{{ $log->target_name }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-[10px] text-gray-600 mono uppercase tracking-widest">
            Showing {{ $logs->firstItem() }} - {{ $logs->lastItem() }} of encrypted records
        </div>
        <div class="cyber-pagination">
            {{ $logs->links() }}
        </div>
    </div>
</div>

</body>
</html>
