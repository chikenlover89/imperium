<div wire:poll.10000ms>
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

    {{-- Section header --}}
    <div class="section-header">
        <div class="section-title">Sensor Nodes</div>
    <div class="last-updated">
        Updated {{ $stats['updated']->format('H:i:s') }}
    </div>
    </div>

    {{-- Empty state --}}
    @if ($devices->isEmpty())
        <div class="empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
            </svg>
            <p>No devices found. Start <code>php artisan mqtt:subscribe</code> and ensure Zigbee2MQTT is running.</p>
        </div>

    {{-- Sensor cards --}}
    @else
        <div class="sensor-grid">
            @foreach ($devices as $device)
                @php
                    $reading = $device->latestReading;
                    $online  = $device->is_online;

                    $battery      = $reading?->battery;
                    $batteryPct   = $battery ?? 0;
                    $batteryColor = match(true) {
                        $batteryPct > 50 => 'var(--green)',
                        $batteryPct > 20 => 'var(--yellow)',
                        default          => 'var(--red)',
                    };
                    $batteryLabel = $battery !== null ? $battery . ' %' : 'N/A';

                    $lq         = $reading?->linkquality ?? 0;
                    $lqPct      = round($lq / 255 * 100);
                    $lqColor    = match(true) {
                        $lq >= 200 => 'var(--green)',
                        $lq >= 140 => 'var(--yellow)',
                        $lq >= 80  => 'var(--orange)',
                        default    => 'var(--red)',
                    };
                    $lqLabel    = $lq > 0 ? "{$lq} LQI" : 'N/A';
                @endphp

                <div class="sensor-card {{ $online ? '' : 'offline' }}" x-data="{open:false}">
                    <div class="card-header">
                        <div class="card-meta">
                            <span class="card-id">{{ $device->model ?? 'Node #' . $device->id }}</span>
                            <span class="card-name">{{ $device->friendly_name }}</span>
                        </div>
                        <span class="status-chip {{ $online ? 'online' : 'offline' }}" wire:loading.remove>
                            <span class="status-dot"></span>
                            {{ $online ? 'Online' : 'Offline' }}
                        </span>
                        <span class="status-chip" wire:loading style="background:rgba(255,255,255,0.06);color:var(--text-2);">
                            <span class="status-dot" style="background:transparent;border:1.5px solid var(--text-2);border-top-color:transparent;animation:spin 0.7s linear infinite;"></span>
                            Syncing
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

                        {{-- Battery + Signal row --}}
                        <div style="display:flex; gap:12px; align-items:flex-end;">

                            {{-- Battery --}}
                            <div class="meter-row" style="flex:1;">
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
                            <div class="meter-row" style="flex:1;">
                                <div class="meter-header">
                                    <div class="meter-left">
                                        <svg class="meter-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                        <span class="meter-label">Signal</span>
                                    </div>
                                    <span class="meter-value" style="color: {{ $lqColor }}">{{ $lqLabel }}</span>
                                </div>
                                <div class="meter-track">
                                    <div class="meter-fill" style="width: {{ $lqPct }}%; background: {{ $lqColor }}"></div>
                                </div>
                            </div>

                        </div>

                        {{-- Last seen + toggle --}}
                        <div class="last-seen-row">
                            <button class="expand-btn" @click="open=!open" :class="{open:open}" title="Show 24h history">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            @if ($device->last_seen_at)
                                <span class="last-seen">Last seen {{ $device->last_seen_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Slide-out graph panel --}}
                    @php
                        $h24       = $device->deviceReadings;
                        $W         = 300;
                        $H         = 44;
                        $endTime   = now()->timestamp;
                        $startTime = $h24->isNotEmpty() ? $h24->first()->created_at->timestamp : $endTime;
                        $duration  = max($endTime - $startTime, 1);

                        // Timestamp-based sparkline: x maps to actual time, not index
                        $sparkPoints = function ($field) use ($h24, $W, $H, $startTime, $duration) {
                            $readings = $h24->whereNotNull($field)->values();
                            if ($readings->count() < 2) return null;
                            $vals  = $readings->pluck($field)->map(fn($v) => (float) $v);
                            $min   = $vals->min();
                            $max   = $vals->max();
                            $range = max($max - $min, 0.5);
                            $pts   = $readings->map(function ($r) use ($field, $W, $H, $startTime, $duration, $min, $range) {
                                $x = round(($r->created_at->timestamp - $startTime) / $duration * $W, 1);
                                $y = round($H - ((float) $r->$field - $min) / $range * ($H - 6) - 3, 1);
                                return "{$x},{$y}";
                            })->implode(' ');
                            $firstX     = round(($readings->first()->created_at->timestamp - $startTime) / $duration * $W, 1);
                            $lastX      = round(($readings->last()->created_at->timestamp  - $startTime) / $duration * $W, 1);
                            $areaPoints = $pts . " {$lastX},{$H} {$firstX},{$H}";
                            return compact('pts', 'areaPoints', 'min', 'max');
                        };

                        $tempSpark = $sparkPoints('temperature');
                        $humSpark  = $sparkPoints('humidity');

                        // 2-hour clock-time boundaries within the actual data window
                        $axisLabels    = [];
                        $firstBoundary = (int) ceil($startTime / 7200) * 7200; // next 2h boundary
                        for ($t = $firstBoundary; $t <= $endTime; $t += 7200) {
                            $axisLabels[] = [
                                'pct'   => round(($t - $startTime) / $duration * 100, 2),
                                'gx'    => round(($t - $startTime) / $duration * $W,  1),
                                'label' => now()->setTimestamp($t)->format('H'),
                            ];
                        }
                    @endphp

                    <div class="graph-panel" :style="open ? 'grid-template-rows:1fr' : 'grid-template-rows:0fr'">
                        <div style="overflow:hidden;">
                            <div class="graph-inner">
                                @if ($tempSpark)
                                    <div class="sparkline-section">
                                        <div class="sparkline-header">
                                            <span class="sparkline-title" style="color:var(--orange)">Temperature</span>
                                            <span class="sparkline-range">{{ $tempSpark['min'] }}° – {{ $tempSpark['max'] }}°C</span>
                                        </div>
                                        <svg viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="none" class="sparkline-svg">
                                            @foreach ($axisLabels as $lbl)
                                                <line x1="{{ $lbl['gx'] }}" y1="0" x2="{{ $lbl['gx'] }}" y2="{{ $H }}" stroke="rgba(255,255,255,0.05)" stroke-width="0.8"/>
                                            @endforeach
                                            <polygon points="{{ $tempSpark['areaPoints'] }}" fill="var(--orange)" fill-opacity="0.10"/>
                                            <polyline points="{{ $tempSpark['pts'] }}" fill="none" stroke="var(--orange)" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                @endif

                                @if ($humSpark)
                                    <div class="sparkline-section">
                                        <div class="sparkline-header">
                                            <span class="sparkline-title" style="color:var(--blue)">Humidity</span>
                                            <span class="sparkline-range">{{ $humSpark['min'] }}% – {{ $humSpark['max'] }}%</span>
                                        </div>
                                        <svg viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="none" class="sparkline-svg">
                                            @foreach ($axisLabels as $lbl)
                                                <line x1="{{ $lbl['gx'] }}" y1="0" x2="{{ $lbl['gx'] }}" y2="{{ $H }}" stroke="rgba(255,255,255,0.05)" stroke-width="0.8"/>
                                            @endforeach
                                            <polygon points="{{ $humSpark['areaPoints'] }}" fill="var(--blue)" fill-opacity="0.10"/>
                                            <polyline points="{{ $humSpark['pts'] }}" fill="none" stroke="var(--blue)" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                @endif

                                @if (!$tempSpark && !$humSpark)
                                    <div class="sparkline-empty">No data for the last 24 hours</div>
                                @endif

                                {{-- X-axis: actual clock hours every 2h within data range --}}
                                <div style="position:relative; height:13px; margin-top:1px;">
                                    @foreach ($axisLabels as $lbl)
                                        <span style="position:absolute; left:{{ $lbl['pct'] }}%; transform:translateX(-50%); font-size:9px; color:var(--text-3); line-height:1; white-space:nowrap;">{{ $lbl['label'] }}</span>
                                    @endforeach
                                    <span style="position:absolute; right:0; font-size:9px; color:var(--text-3); line-height:1;">now</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
