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

        /* ── Sidebar ── */
        .sidebar {
            width: 240px; flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            padding: 24px 0;
            position: sticky; top: 0; height: 100vh;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 0 20px 28px;
            border-bottom: 1px solid var(--border);
        }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), #6366f1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon svg { width: 18px; height: 18px; color: #fff; }
        .brand-name { font-size: 16px; font-weight: 700; letter-spacing: 0.04em; color: var(--text-1); }

        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-3); padding: 0 8px; margin-bottom: 6px; margin-top: 16px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: var(--text-2); font-size: 13.5px; font-weight: 500;
            cursor: pointer; transition: all 0.15s;
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--text-1); }
        .nav-item.active { background: var(--blue-dim); color: var(--blue); }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sidebar-footer { padding: 16px 20px; border-top: 1px solid var(--border); }
        .live-badge { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-2); }
        .pulse { width: 8px; height: 8px; border-radius: 50%; background: var(--green); box-shadow: 0 0 0 0 rgba(34,197,94,0.5); animation: ripple 1.5s infinite; }
        @keyframes ripple { 0%{box-shadow:0 0 0 0 rgba(34,197,94,0.5)} 70%{box-shadow:0 0 0 8px rgba(34,197,94,0)} 100%{box-shadow:0 0 0 0 rgba(34,197,94,0)} }

        /* ── Main content ── */
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
            border: none; cursor: pointer;
        }
        .btn-login:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-login svg { width: 14px; height: 14px; }

        .content-inner { padding: 28px 32px; flex: 1; }

        .page-header { margin-bottom: 28px; }

        /* ── Stat row ── */
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
        .stat-value { font-size: 32px; font-weight: 700; line-height: 1; color: var(--text-1); }
        .stat-sub { font-size: 12px; color: var(--text-2); margin-top: 6px; }

        /* ── Section header ── */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 15px; font-weight: 600; color: var(--text-1); }
        .section-action { font-size: 12px; color: var(--blue); cursor: pointer; }

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
        .sensor-card.offline { opacity: 0.55; pointer-events: none; }

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

        /* readings row */
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
        .reading-unit { font-size: 13px; font-weight: 400; color: var(--text-3); }

        /* battery */
        .meter-row { display: flex; flex-direction: column; gap: 6px; }
        .meter-header { display: flex; align-items: center; justify-content: space-between; }
        .meter-left { display: flex; align-items: center; gap: 6px; }
        .meter-icon { width: 14px; height: 14px; color: var(--text-2); }
        .meter-label { font-size: 12px; color: var(--text-2); font-weight: 500; }
        .meter-value { font-size: 12px; font-weight: 600; }
        .meter-track { height: 5px; background: rgba(255,255,255,0.08); border-radius: 99px; overflow: hidden; }
        .meter-fill { height: 100%; border-radius: 99px; transition: width 0.6s ease; }

        /* signal */
        .signal-row { display: flex; align-items: center; justify-content: space-between; }
        .signal-left { display: flex; align-items: center; gap: 6px; }
        .signal-bars { display: flex; align-items: flex-end; gap: 2px; height: 14px; }
        .bar { width: 3px; border-radius: 1px; background: rgba(255,255,255,0.15); }
        .bar.lit { background: var(--green); }

        .divider { height: 1px; background: var(--border); }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .sidebar { display: none; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .topbar { padding: 12px 16px; }
            .content-inner { padding: 20px 16px; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .readings { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
<div class="layout">

    <!-- ── Sidebar ── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <span class="brand-name">IMPERIUM</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a class="nav-item active" href="#">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            <a class="nav-item" href="#">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Analytics
            </a>

            <div class="nav-label">Devices</div>
            <a class="nav-item" href="#">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.071 4.929a10 10 0 010 14.142M4.929 4.929a10 10 0 000 14.142"/></svg>
                Sensors
            </a>
            <a class="nav-item" href="#">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Alerts
            </a>

            <div class="nav-label">System</div>
            <a class="nav-item" href="#">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="live-badge">
                <span class="pulse"></span>
                System live
            </div>
        </div>
    </aside>

    <!-- ── Main ── -->
    <main class="content">

        <!-- Top bar -->
        <div class="topbar">
            <div class="topbar-left">
                <div class="page-title">Sensor Overview</div>
                <div class="page-subtitle">Real-time monitoring &mdash; temperature &amp; humidity nodes</div>
            </div>
            <a href="/login" class="btn-login">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Sign In
            </a>
        </div>

        <div class="content-inner">

        <!-- Stats -->
        <div class="stat-grid">
            <div class="stat-card" style="--accent: var(--blue)">
                <div class="stat-label">Total Sensors</div>
                <div class="stat-value">4</div>
                <div class="stat-sub">Registered nodes</div>
            </div>
            <div class="stat-card" style="--accent: var(--green)">
                <div class="stat-label">Online</div>
                <div class="stat-value" style="color: var(--green)">3</div>
                <div class="stat-sub">Reporting normally</div>
            </div>
            <div class="stat-card" style="--accent: var(--red)">
                <div class="stat-label">Offline</div>
                <div class="stat-value" style="color: var(--red)">1</div>
                <div class="stat-sub">No signal</div>
            </div>
            <div class="stat-card" style="--accent: var(--orange)">
                <div class="stat-label">Avg. Temperature</div>
                <div class="stat-value" style="color: var(--orange)">--</div>
                <div class="stat-sub">Waiting for data</div>
            </div>
        </div>

        <!-- Sensors -->
        <div class="section-header">
            <div class="section-title">Sensor Nodes</div>
            <div class="section-action">View all</div>
        </div>

        <div class="sensor-grid">

            {{-- ── Sensor 01 ── --}}
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-meta">
                        <span class="card-id">Node #01</span>
                        <span class="card-name">Living Room</span>
                    </div>
                    <span class="status-chip online"><span class="status-dot"></span>Online</span>
                </div>
                <div class="card-body">
                    <div class="readings">
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon temp">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4a3 3 0 116 0v5a5 5 0 11-6 0z"/></svg>
                                </div>
                                <span class="reading-label">Temp</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">°C</span></div>
                        </div>
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon humid">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5S5 10 5 14.5a7 7 0 0014 0C19 10 12 3.5 12 3.5z"/></svg>
                                </div>
                                <span class="reading-label">Humidity</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">%</span></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="meter-row">
                        <div class="meter-header">
                            <div class="meter-left">
                                <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/></svg>
                                <span class="meter-label">Battery</span>
                            </div>
                            <span class="meter-value" style="color:var(--green)">--%</span>
                        </div>
                        <div class="meter-track"><div class="meter-fill" style="width:0%;background:var(--green)"></div></div>
                    </div>
                    <div class="signal-row">
                        <div class="signal-left">
                            <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            <span class="meter-label">Signal</span>
                        </div>
                        <div class="signal-bars">
                            <div class="bar" style="height:4px"></div>
                            <div class="bar" style="height:6px"></div>
                            <div class="bar" style="height:9px"></div>
                            <div class="bar" style="height:12px"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sensor 02 ── --}}
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-meta">
                        <span class="card-id">Node #02</span>
                        <span class="card-name">Bedroom</span>
                    </div>
                    <span class="status-chip online"><span class="status-dot"></span>Online</span>
                </div>
                <div class="card-body">
                    <div class="readings">
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon temp">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4a3 3 0 116 0v5a5 5 0 11-6 0z"/></svg>
                                </div>
                                <span class="reading-label">Temp</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">°C</span></div>
                        </div>
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon humid">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5S5 10 5 14.5a7 7 0 0014 0C19 10 12 3.5 12 3.5z"/></svg>
                                </div>
                                <span class="reading-label">Humidity</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">%</span></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="meter-row">
                        <div class="meter-header">
                            <div class="meter-left">
                                <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/></svg>
                                <span class="meter-label">Battery</span>
                            </div>
                            <span class="meter-value" style="color:var(--yellow)">--%</span>
                        </div>
                        <div class="meter-track"><div class="meter-fill" style="width:0%;background:var(--yellow)"></div></div>
                    </div>
                    <div class="signal-row">
                        <div class="signal-left">
                            <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            <span class="meter-label">Signal</span>
                        </div>
                        <div class="signal-bars">
                            <div class="bar" style="height:4px"></div>
                            <div class="bar" style="height:6px"></div>
                            <div class="bar" style="height:9px"></div>
                            <div class="bar" style="height:12px"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sensor 03 ── --}}
            <div class="sensor-card">
                <div class="card-header">
                    <div class="card-meta">
                        <span class="card-id">Node #03</span>
                        <span class="card-name">Kitchen</span>
                    </div>
                    <span class="status-chip online"><span class="status-dot"></span>Online</span>
                </div>
                <div class="card-body">
                    <div class="readings">
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon temp">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4a3 3 0 116 0v5a5 5 0 11-6 0z"/></svg>
                                </div>
                                <span class="reading-label">Temp</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">°C</span></div>
                        </div>
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon humid">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5S5 10 5 14.5a7 7 0 0014 0C19 10 12 3.5 12 3.5z"/></svg>
                                </div>
                                <span class="reading-label">Humidity</span>
                            </div>
                            <div class="reading-value">--<span class="reading-unit">%</span></div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="meter-row">
                        <div class="meter-header">
                            <div class="meter-left">
                                <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/></svg>
                                <span class="meter-label">Battery</span>
                            </div>
                            <span class="meter-value" style="color:var(--green)">--%</span>
                        </div>
                        <div class="meter-track"><div class="meter-fill" style="width:0%;background:var(--green)"></div></div>
                    </div>
                    <div class="signal-row">
                        <div class="signal-left">
                            <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            <span class="meter-label">Signal</span>
                        </div>
                        <div class="signal-bars">
                            <div class="bar" style="height:4px"></div>
                            <div class="bar" style="height:6px"></div>
                            <div class="bar" style="height:9px"></div>
                            <div class="bar" style="height:12px"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sensor 04 (offline) ── --}}
            <div class="sensor-card offline">
                <div class="card-header">
                    <div class="card-meta">
                        <span class="card-id">Node #04</span>
                        <span class="card-name">Garage</span>
                    </div>
                    <span class="status-chip offline"><span class="status-dot"></span>Offline</span>
                </div>
                <div class="card-body">
                    <div class="readings">
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon temp">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4a3 3 0 116 0v5a5 5 0 11-6 0z"/></svg>
                                </div>
                                <span class="reading-label">Temp</span>
                            </div>
                            <div class="reading-value" style="color:var(--text-3)">N/A</div>
                        </div>
                        <div class="reading-box">
                            <div class="reading-header">
                                <div class="reading-icon humid">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5S5 10 5 14.5a7 7 0 0014 0C19 10 12 3.5 12 3.5z"/></svg>
                                </div>
                                <span class="reading-label">Humidity</span>
                            </div>
                            <div class="reading-value" style="color:var(--text-3)">N/A</div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="meter-row">
                        <div class="meter-header">
                            <div class="meter-left">
                                <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="18" height="10" rx="2"/><path d="M22 11v2"/></svg>
                                <span class="meter-label">Battery</span>
                            </div>
                            <span class="meter-value" style="color:var(--text-3)">N/A</span>
                        </div>
                        <div class="meter-track"><div class="meter-fill" style="width:0%;background:var(--text-3)"></div></div>
                    </div>
                    <div class="signal-row">
                        <div class="signal-left">
                            <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            <span class="meter-label">Signal</span>
                        </div>
                        <div class="signal-bars">
                            <div class="bar" style="height:4px"></div>
                            <div class="bar" style="height:6px"></div>
                            <div class="bar" style="height:9px"></div>
                            <div class="bar" style="height:12px"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>{{-- end content-inner --}}
    </main>
</div>
</body>
</html>
