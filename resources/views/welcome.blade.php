<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Imperium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:        #0a0e1a;
            --surface:   #111827;
            --surface2:  #1a2235;
            --border:    rgba(255,255,255,0.07);
            --text-1:    #f0f4ff;
            --text-2:    #8b9ab4;
            --text-3:    #4e6080;
            --blue:      #3b82f6;
            --blue-dim:  rgba(59,130,246,0.12);
            --green:     #22c55e;
            --green-dim: rgba(34,197,94,0.12);
            --yellow:    #f59e0b;
            --yellow-dim:rgba(245,158,11,0.12);
            --red:       #ef4444;
            --red-dim:   rgba(239,68,68,0.12);
            --orange:    #f97316;
            --radius:    12px;
            --shadow:    0 1px 3px rgba(0,0,0,0.4), 0 4px 16px rgba(0,0,0,0.3);
        }
        html, body { height: 100%; background: var(--bg); color: var(--text-1); font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.5; }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }

        .brand {
            display: flex; align-items: center; gap: 10px;
            padding: 0 20px 28px;
        }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), #6366f1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg { width: 18px; height: 18px; color: #fff; }
        .brand-name { font-size: 16px; font-weight: 700; letter-spacing: 0.04em; color: var(--text-1); }

        /* ── Main ── */
        .content { flex: 1; overflow: auto; display: flex; flex-direction: column; }

        .topbar {
            position: sticky; top: 0; z-index: 10;
            background: rgba(10,14,26,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 14px 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-left { display: flex; flex-direction: column; gap: 2px; }
        .page-title { font-size: 18px; font-weight: 700; color: var(--text-1); }
        .page-subtitle { font-size: 12px; color: var(--text-2); }

        .btn-login {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; border-radius: 8px;
            background: var(--blue); color: #fff;
            font-size: 13px; font-weight: 600;
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
        }
        .btn-login:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-login svg { width: 14px; height: 14px; }

        .content-inner { padding: 28px 32px; flex: 1; }

        /* ── Stats ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: var(--accent, var(--blue));
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-3); margin-bottom: 8px; }
        .stat-value { font-size: 32px; font-weight: 700; line-height: 1; }
        .stat-sub { font-size: 12px; color: var(--text-2); margin-top: 6px; }

        /* ── Section ── */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 15px; font-weight: 600; color: var(--text-1); }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 64px 32px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .empty-state svg { width: 40px; height: 40px; color: var(--text-3); margin: 0 auto 12px; }
        .empty-state p { color: var(--text-3); font-size: 13px; }

        /* ── Sensor grid ── */
        .sensor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .sensor-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .sensor-card:hover { transform: translateY(-2px); box-shadow: 0 4px 24px rgba(0,0,0,0.4); }
        .sensor-card.offline { opacity: 0.55; }

        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
        }
        .card-meta { display: flex; flex-direction: column; gap: 2px; }
        .card-id { font-size: 10px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-3); }
        .card-name { font-size: 14px; font-weight: 600; color: var(--text-1); }

        .status-chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 99px;
            font-size: 11px; font-weight: 600;
        }
        .status-chip.online  { background: var(--green-dim); color: var(--green); }
        .status-chip.offline { background: var(--red-dim);   color: var(--red); }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .card-body { padding: 18px; display: flex; flex-direction: column; gap: 16px; }

        .readings { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .reading-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px; padding: 12px 14px;
        }
        .reading-header { display: flex; align-items: center; gap: 7px; margin-bottom: 8px; }
        .reading-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .reading-icon.temp  { background: rgba(249,115,22,0.12); }
        .reading-icon.humid { background: rgba(59,130,246,0.12); }
        .reading-icon svg { width: 14px; height: 14px; }
        .reading-icon.temp  svg { color: var(--orange); }
        .reading-icon.humid svg { color: var(--blue); }
        .reading-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--text-3); }
        .reading-value { font-size: 24px; font-weight: 700; color: var(--text-1); line-height: 1; }
        .reading-unit  { font-size: 13px; font-weight: 400; color: var(--text-3); }
        .reading-stale { font-size: 12px; color: var(--text-3); font-style: italic; }

        .meter-row { display: flex; flex-direction: column; gap: 6px; }
        .meter-header { display: flex; align-items: center; justify-content: space-between; }
        .meter-left { display: flex; align-items: center; gap: 6px; }
        .meter-icon { width: 14px; height: 14px; color: var(--text-2); }
        .meter-label { font-size: 12px; color: var(--text-2); font-weight: 500; }
        .meter-value { font-size: 12px; font-weight: 600; }
        .meter-track { height: 5px; background: rgba(255,255,255,0.08); border-radius: 99px; overflow: hidden; }
        .meter-fill  { height: 100%; border-radius: 99px; }

        .signal-row  { display: flex; align-items: center; justify-content: space-between; }
        .signal-left { display: flex; align-items: center; gap: 6px; }
        .signal-bars { display: flex; align-items: flex-end; gap: 2px; height: 14px; }
        .bar { width: 3px; border-radius: 1px; background: rgba(255,255,255,0.12); }
        .bar.lit { background: var(--green); }

        .divider { height: 1px; background: var(--border); }

        .last-seen { font-size: 11px; color: var(--text-3); text-align: right; }

        @media (max-width: 900px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .topbar { padding: 12px 16px; }
            .content-inner { padding: 20px 16px; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
<div class="layout">

    {{-- ── Main ── --}}
    <main class="content">

        {{-- Top bar --}}
        <div class="topbar">
            <div class="topbar-left">
                <div class="brand">
                    <div class="brand-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>
                    </div>
                    <span class="brand-name">IMPERIUM</span>
                </div>
            </div>
            <a href="/login" class="btn-login">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Sign In
            </a>
        </div>

        <div class="content-inner">

            {{-- Stats --}}
            <div class="stat-grid">
                <div class="stat-card" style="--accent: var(--blue)">
                    <div class="stat-label">Total Sensors</div>
                    <div class="stat-value" style="color: var(--text-1)">{{ $stats['total'] }}</div>
                    <div class="stat-sub">Registered nodes</div>
                </div>
                <div class="stat-card" style="--accent: var(--green)">
                    <div class="stat-label">Online</div>
                    <div class="stat-value" style="color: var(--green)">{{ $stats['online'] }}</div>
                    <div class="stat-sub">Reporting normally</div>
                </div>
                <div class="stat-card" style="--accent: var(--red)">
                    <div class="stat-label">Offline</div>
                    <div class="stat-value" style="color: var(--red)">{{ $stats['offline'] }}</div>
                    <div class="stat-sub">No signal</div>
                </div>
                <div class="stat-card" style="--accent: var(--orange)">
                    <div class="stat-label">Avg. Temperature</div>
                    <div class="stat-value" style="color: var(--orange)">
                        {{ $stats['avg_temp'] !== null ? number_format($stats['avg_temp'], 1) . '°' : '--' }}
                    </div>
                    <div class="stat-sub">Across all sensors</div>
                </div>
            </div>

            {{-- Devices --}}
            <div class="section-header">
                <div class="section-title">Sensor Nodes</div>
            </div>

            @if ($devices->isEmpty())
                <div class="empty-state">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                    <p>No devices found. Start <code>php artisan mqtt:subscribe</code> and ensure Zigbee2MQTT is running.</p>
                </div>
            @else
                <div class="sensor-grid">
                    @foreach ($devices as $device)
                        @php
                            $reading = $device->latestReading;
                            $online  = $device->is_online;

                            // Battery colour
                            $battery     = $reading?->battery;
                            $batteryPct  = $battery ?? 0;
                            $batteryColor = match(true) {
                                $batteryPct > 50 => 'var(--green)',
                                $batteryPct > 20 => 'var(--yellow)',
                                default          => 'var(--red)',
                            };
                            $batteryLabel = $battery !== null ? $battery . '%' : 'N/A';

                            // Signal bars (linkquality 0–255)
                            $lq   = $reading?->linkquality ?? 0;
                            $bars = match(true) {
                                $lq >= 200 => 4,
                                $lq >= 140 => 3,
                                $lq >= 80  => 2,
                                $lq > 0    => 1,
                                default    => 0,
                            };
                        @endphp

                        <div class="sensor-card {{ $online ? '' : 'offline' }}">
                            <div class="card-header">
                                <div class="card-meta">
                                    <span class="card-id">{{ $device->model ?? 'Node #' . $device->id }}</span>
                                    <span class="card-name">{{ $device->friendly_name }}</span>
                                </div>
                                <span class="status-chip {{ $online ? 'online' : 'offline' }}">
                                    <span class="status-dot"></span>
                                    {{ $online ? 'Online' : 'Offline' }}
                                </span>
                            </div>

                            <div class="card-body">
                                {{-- Temperature & Humidity --}}
                                <div class="readings">
                                    <div class="reading-box">
                                        <div class="reading-header">
                                            <div class="reading-icon temp">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4a3 3 0 116 0v5a5 5 0 11-6 0z"/></svg>
                                            </div>
                                            <span class="reading-label">Temp</span>
                                        </div>
                                        @if ($reading?->temperature !== null)
                                            <div class="reading-value">
                                                {{ number_format($reading->temperature, 1) }}<span class="reading-unit">°C</span>
                                            </div>
                                        @else
                                            <div class="reading-stale">No data</div>
                                        @endif
                                    </div>

                                    <div class="reading-box">
                                        <div class="reading-header">
                                            <div class="reading-icon humid">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5S5 10 5 14.5a7 7 0 0014 0C19 10 12 3.5 12 3.5z"/></svg>
                                            </div>
                                            <span class="reading-label">Humidity</span>
                                        </div>
                                        @if ($reading?->humidity !== null)
                                            <div class="reading-value">
                                                {{ number_format($reading->humidity, 1) }}<span class="reading-unit">%</span>
                                            </div>
                                        @else
                                            <div class="reading-stale">No data</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Battery --}}
                                <div class="meter-row">
                                    <div class="meter-header">
                                        <div class="meter-left">
                                            <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/></svg>
                                            <span class="meter-label">Battery</span>
                                        </div>
                                        <span class="meter-value" style="color: {{ $batteryColor }}">{{ $batteryLabel }}</span>
                                    </div>
                                    <div class="meter-track">
                                        <div class="meter-fill" style="width: {{ $batteryPct }}%; background: {{ $batteryColor }}"></div>
                                    </div>
                                </div>

                                {{-- Signal --}}
                                <div class="signal-row">
                                    <div class="signal-left">
                                        <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                        <span class="meter-label">Signal{{ $lq > 0 ? ' (' . $lq . ')' : '' }}</span>
                                    </div>
                                    <div class="signal-bars">
                                        <div class="bar {{ $bars >= 1 ? 'lit' : '' }}" style="height:4px"></div>
                                        <div class="bar {{ $bars >= 2 ? 'lit' : '' }}" style="height:6px"></div>
                                        <div class="bar {{ $bars >= 3 ? 'lit' : '' }}" style="height:9px"></div>
                                        <div class="bar {{ $bars >= 4 ? 'lit' : '' }}" style="height:12px"></div>
                                    </div>
                                </div>

                                {{-- Last seen --}}
                                @if ($device->last_seen_at)
                                    <div class="last-seen">Last seen {{ $device->last_seen_at->diffForHumans() }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>{{-- end content-inner --}}
    </main>
</div>
</body>
</html>
