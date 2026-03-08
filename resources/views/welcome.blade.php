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
    @livewireStyles
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

        .btn-sound {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 8px; border: 1px solid var(--border);
            background: transparent; color: var(--text-3); cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }
        .btn-sound:hover { background: rgba(255,255,255,0.06); color: var(--text-1); }
        .btn-sound.active { color: var(--blue); border-color: var(--blue); }
        .btn-sound svg { width: 16px; height: 16px; }

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
        .sensor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 16px; align-items: start; }
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

        .last-seen    { font-size: 11px; color: var(--text-3); flex: 1; text-align: right; }
        .last-updated { font-size: 11px; color: var(--text-3); }

        .last-seen-row { display: flex; align-items: center; gap: 8px; }

        .expand-btn {
            width: 20px; height: 20px; flex-shrink: 0;
            background: rgba(255,255,255,0.05); border: 1px solid var(--border);
            border-radius: 5px; cursor: pointer; color: var(--text-3);
            display: flex; align-items: center; justify-content: center;
            padding: 0; transition: background 0.15s, color 0.15s;
        }
        .expand-btn:hover { background: rgba(255,255,255,0.1); color: var(--text-1); }
        .expand-btn svg { width: 11px; height: 11px; transition: transform 0.25s ease; }
        .expand-btn.open svg { transform: rotate(180deg); }

        .graph-panel {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease;
        }
        .graph-inner {
            padding: 14px 18px 16px;
            display: flex; flex-direction: column; gap: 10px;
            border-top: 1px solid var(--border);
        }
        .sparkline-section { display: flex; flex-direction: column; gap: 5px; }
        .sparkline-header  { display: flex; align-items: center; justify-content: space-between; }
        .sparkline-title   { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; }
        .sparkline-range   { font-size: 10px; color: var(--text-3); }
        .sparkline-svg     { width: 100%; height: 44px; display: block; border-radius: 4px; overflow: hidden; }
        .sparkline-empty   { font-size: 11px; color: var(--text-3); font-style: italic; text-align: center; padding: 6px 0; }
        .sparkline-time-labels {
            display: flex; justify-content: space-between;
            font-size: 10px; color: var(--text-3);
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Smart plug card ── */
        .plug-top { display: flex; align-items: flex-start; justify-content: space-between; }
        .plug-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .plug-stat { display: flex; flex-direction: column; gap: 4px; background: var(--surface2); border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px; }
        .plug-stat-value { font-size: 16px; font-weight: 700; color: var(--text-1); }

        /* ── Event History ── */
        .event-log-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 16px; }

        .event-log-card {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            background: var(--surface); border: 1px solid var(--border); border-radius: 10px;
            padding: 12px 16px; transition: opacity 0.2s;
        }
        .event-log-card.resolved { opacity: 0.4; }

        .event-log-left  { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .event-log-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

        .resolve-btn {
            width: 22px; height: 22px; flex-shrink: 0;
            border-radius: 6px; border: 1.5px solid var(--border);
            background: transparent; cursor: pointer; color: transparent;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }
        .resolve-btn svg { width: 12px; height: 12px; }
        .resolve-btn:not(.checked):hover { border-color: var(--green); color: var(--green); }
        .resolve-btn.checked { background: var(--green); border-color: var(--green); color: #fff; cursor: default; }

        .event-log-info  { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .event-log-name  { font-size: 13px; font-weight: 600; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .event-log-context { display: flex; flex-wrap: wrap; gap: 6px; }
        .event-log-context span { font-size: 10px; color: var(--text-3); padding: 1px 6px; border-radius: 4px; }

        .event-log-type { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.06em; }
        .event-log-type.device_alert { background: rgba(255,100,100,0.12); color: var(--red); }
        .event-log-type.once         { background: rgba(100,160,255,0.12); color: var(--blue); }
        .event-log-type.repeating    { background: rgba(255,165,0,0.12);   color: var(--orange); }

        .resolve-confirm {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: var(--text-2);
        }
        .confirm-yes, .confirm-no {
            padding: 2px 10px; border-radius: 5px; font-size: 11px;
            font-weight: 600; cursor: pointer; border: 1px solid var(--border);
            transition: background 0.15s, color 0.15s;
        }
        .confirm-yes { background: var(--green); color: #fff; border-color: var(--green); }
        .confirm-yes:hover { filter: brightness(1.15); }
        .confirm-no  { background: transparent; color: var(--text-2); }
        .confirm-no:hover  { background: rgba(255,255,255,0.08); }

        .event-log-time { font-size: 11px; color: var(--text-3); white-space: nowrap; }

        @media (max-width: 900px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .topbar { padding: 12px 16px; }
            .content-inner { padding: 20px 16px; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
        }

        @keyframes pulse-alert {
            0%, 100% { background: transparent; }
            50%       { background: rgba(26,5,8,0.85); }
        }
        #alert-overlay {
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none; background: transparent;
        }
        body.has-unresolved #alert-overlay { animation: pulse-alert 2.5s ease-in-out infinite; }
        .layout { position: relative; z-index: 1; }
    </style>
</head>
<body x-data="{}" @unresolved-status.window="handleUnresolvedStatus($event.detail.hasUnresolved)">
<div id="alert-overlay"></div>
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
            <div style="display:flex;align-items:center;gap:10px;">
                <button class="btn-sound" id="btn-sound" title="Toggle alert sound" onclick="toggleAlertSound()">
                    {{-- icon swapped by JS --}}
                    <svg id="icon-sound-on" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M9 9H5a1 1 0 00-1 1v4a1 1 0 001 1h4l3 3V6L9 9z"/>
                    </svg>
                    <svg id="icon-sound-off" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                </button>
                <a href="/login" class="btn-login">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Sign In
                </a>
            </div>
        </div>

        <div class="content-inner">

            <livewire:sensor-dashboard />

        </div>{{-- end content-inner --}}
    </main>
</div>
@livewireScripts
<script>
    let _audioCtx    = null;
    let _alertLoop   = null;
    let _pendingStart = false;
    let _soundEnabled = localStorage.getItem('alertSound') === 'true';

    function getAudioCtx() {
        if (!_audioCtx) _audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        return _audioCtx;
    }

    function playBeep() {
        try {
            const ctx = getAudioCtx();
            [880, 660].forEach((freq, i) => {
                const osc  = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.18);
                gain.gain.setValueAtTime(0, ctx.currentTime + i * 0.18);
                gain.gain.linearRampToValueAtTime(0.35, ctx.currentTime + i * 0.18 + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.18 + 0.18);
                osc.start(ctx.currentTime + i * 0.18);
                osc.stop(ctx.currentTime + i * 0.18 + 0.18);
            });
        } catch (e) {}
    }

    function startAlertLoop() {
        if (_alertLoop || !_soundEnabled) return;
        playBeep();
        _alertLoop = setInterval(playBeep, 4000);
    }

    function stopAlertLoop() {
        clearInterval(_alertLoop);
        _alertLoop = null;
    }

    function updateSoundButton() {
        const btn = document.getElementById('btn-sound');
        document.getElementById('icon-sound-on').style.display  = _soundEnabled ? '' : 'none';
        document.getElementById('icon-sound-off').style.display = _soundEnabled ? 'none' : '';
        btn.classList.toggle('active', _soundEnabled);
    }

    function toggleAlertSound() {
        _soundEnabled = !_soundEnabled;
        localStorage.setItem('alertSound', _soundEnabled);
        updateSoundButton();
        if (!_soundEnabled) {
            stopAlertLoop();
            _pendingStart = false;
        }
    }

    document.addEventListener('DOMContentLoaded', updateSoundButton);

    // Warm up AudioContext on first user gesture; start loop if pending
    document.addEventListener('click', () => {
        getAudioCtx().resume().then(() => {
            if (_pendingStart && _soundEnabled) { _pendingStart = false; startAlertLoop(); }
        });
    }, { once: true });

    function handleUnresolvedStatus(hasUnresolved) {
        document.body.classList.toggle('has-unresolved', hasUnresolved);
        if (hasUnresolved && !_alertLoop) {
            if (_soundEnabled) {
                if (_audioCtx && _audioCtx.state === 'running') {
                    startAlertLoop();
                } else {
                    _pendingStart = true;
                }
            }
        } else if (!hasUnresolved) {
            stopAlertLoop();
            _pendingStart = false;
        }
    }
</script>
</body>
</html>
