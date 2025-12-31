<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Control Center | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', sans-serif; background-color: #010409; color: #f0fdff; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .cyber-border { border: 1px solid rgba(0, 243, 255, 0.2); }
        .neon-text { text-shadow: 0 0 10px rgba(0, 243, 255, 0.5); }
    </style>
</head>
<body class="p-8">

<div class="max-w-7xl mx-auto">
    <header class="mb-10 flex justify-between items-center border-b border-cyan-900 pb-5">
        <h1 class="text-3xl font-bold neon-text uppercase tracking-widest">System Logs <span class="text-sm font-light text-cyan-500">v1.0</span></h1>
        <div class="text-left">
            <p class="text-xs text-cyan-700">SERVER STATUS: <span class="text-green-500 italic">ONLINE</span></p>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="cyber-border bg-gray-900/50 p-6 rounded-lg">
            <p class="text-cyan-500 text-sm">کل بازدیدها</p>
            <h3 class="text-4xl font-bold mono">{{ $totalVisits }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/50 p-6 rounded-lg">
            <p class="text-cyan-500 text-sm">کل کلیک‌ها</p>
            <h3 class="text-4xl font-bold mono text-pink-500">{{ $totalClicks }}</h3>
        </div>
        <div class="cyber-border bg-gray-900/50 p-6 rounded-lg">
            <p class="text-cyan-500 text-sm">مرورگر برتر</p>
            <h3 class="text-2xl font-bold mono">{{ $topBrowser->browser ?? 'N/A' }}</h3>
        </div>
    </div>

    <div class="cyber-border bg-gray-900/30 rounded-xl overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-cyan-950/50 text-cyan-400">
            <tr>
                <th class="p-4">زمان</th>
                <th class="p-4">IP / موقعیت</th>
                <th class="p-4">نوع</th>
                <th class="p-4">سخت‌افزار (GPU/CPU)</th>
                <th class="p-4">هدف کلیک</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
            @foreach($logs as $log)
                <tr class="hover:bg-cyan-900/10 transition-colors">
                    <td class="p-4 mono text-xs">{{ $log->created_at }}</td>
                    <td class="p-4">
                        <span class="block mono text-sm">{{ $log->ip_address }}</span>
                        <span class="text-[10px] text-gray-500">{{ $log->platform }} | {{ $log->browser }}</span>
                    </td>
                    <td class="p-4">
                            <span class="px-2 py-1 rounded text-[10px] {{ $log->event_type == 'click' ? 'bg-pink-900/30 text-pink-400' : 'bg-green-900/30 text-green-400' }}">
                                {{ strtoupper($log->event_type) }}
                            </span>
                    </td>
                    <td class="p-4 text-xs text-gray-400">
                        <span class="block">GPU: {{ Str::limit($log->gpu, 30) }}</span>
                        <span>Cores: {{ $log->cpu_threads }} | Res: {{ $log->screen_res }}</span>
                    </td>
                    <td class="p-4 text-cyan-300 font-bold">
                        {{ $log->target_name ?? '-' }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 mono">
        {{ $logs->links() }}
    </div>
</div>

</body>
</html>
