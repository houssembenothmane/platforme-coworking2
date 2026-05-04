@extends('layouts.app')
@section('title', 'Plan interactif')

@php
// CORRECTION 3 : sécurisation des variables pour éviter les erreurs si non passées par le contrôleur
$occupiedIds = $occupiedIds ?? [];
$soonIds     = $soonIds     ?? [];

$layout = [
    ['name' => 'Open Space – Zone Silence',         'x' => 20,  'y' => 20,  'w' => 300, 'h' => 140, 'type' => 'open',      'rows' => 4, 'cols' => 5, 'door' => ['x'=>170,'y'=>160,'side'=>'bottom']],
    ['name' => 'Open Space – Zone Collaborative',   'x' => 20,  'y' => 170, 'w' => 300, 'h' => 140, 'type' => 'cluster',   'rows' => 2, 'cols' => 2, 'door' => ['x'=>170,'y'=>310,'side'=>'bottom']],
    ['name' => 'Salle de Réunion – Le Carthage',    'x' => 330, 'y' => 20,  'w' => 180, 'h' => 140, 'type' => 'meeting',   'chairs' => 8, 'door' => ['x'=>420,'y'=>160,'side'=>'bottom']],
    ['name' => 'Bureau Privé n°1',                  'x' => 520, 'y' => 20,  'w' => 160, 'h' => 65,  'type' => 'office',    'chairs' => 2, 'door' => ['x'=>520,'y'=>50,'side'=>'left']],
    ['name' => 'Bureau Privé n°2',                  'x' => 520, 'y' => 95,  'w' => 160, 'h' => 65,  'type' => 'office',    'chairs' => 2, 'door' => ['x'=>520,'y'=>125,'side'=>'left']],
    ['name' => 'Salle de Réunion – Le Sahara',      'x' => 330, 'y' => 170, 'w' => 180, 'h' => 80,  'type' => 'meeting',   'chairs' => 6, 'door' => ['x'=>330,'y'=>210,'side'=>'left']],
    ['name' => 'Phone Booth n°1',                   'x' => 330, 'y' => 260, 'w' => 85,  'h' => 50,  'type' => 'booth',     'door' => ['x'=>370,'y'=>260,'side'=>'top']],
    ['name' => 'Salle de Réunion – Le Médina',      'x' => 425, 'y' => 260, 'w' => 85,  'h' => 50,  'type' => 'meeting',   'chairs' => 4, 'door' => ['x'=>465,'y'=>260,'side'=>'top']],
    ['name' => 'Studio Créatif',                    'x' => 520, 'y' => 170, 'w' => 160, 'h' => 140, 'type' => 'studio',    'door' => ['x'=>520,'y'=>240,'side'=>'left']],
    ['name' => 'Lounge Café – Zone Détente',        'x' => 20,  'y' => 320, 'w' => 240, 'h' => 170, 'type' => 'lounge',    'door' => ['x'=>140,'y'=>320,'side'=>'top']],
    ['name' => "Salle Conférence – L'Auditorium",   'x' => 270, 'y' => 320, 'w' => 410, 'h' => 170, 'type' => 'auditorium','door' => ['x'=>475,'y'=>320,'side'=>'top']],
];

$espacesByName = $espaces->keyBy('nom');

$getStatus = function($espace) use ($occupiedIds, $soonIds) {
    if (!$espace) return ['fill' => '#e2e8f0', 'stroke' => '#94a3b8', 'label' => 'N/A'];
    if ($espace->statut === 'Indisponible')  return ['fill' => '#94a3b8', 'stroke' => '#475569', 'label' => 'Indisponible'];
    if (in_array($espace->id, $occupiedIds)) return ['fill' => '#ef4444', 'stroke' => '#b91c1c', 'label' => 'Occupée'];
    if (in_array($espace->id, $soonIds))     return ['fill' => '#f59e0b', 'stroke' => '#b45309', 'label' => 'Bientôt'];
    return ['fill' => '#22c55e', 'stroke' => '#15803d', 'label' => 'Disponible'];
};

$counts = ['libre' => 0, 'occupe' => 0, 'bientot' => 0, 'indispo' => 0];
foreach ($espaces as $e) {
    if ($e->statut === 'Indisponible')      $counts['indispo']++;
    elseif (in_array($e->id, $occupiedIds)) $counts['occupe']++;
    elseif (in_array($e->id, $soonIds))     $counts['bientot']++;
    else                                    $counts['libre']++;
}

$myEspaceId = $myReservation?->espace_id;

$myPathD = '';
if ($myReservation) {
    $myRoom = collect($layout)->firstWhere('name', $myReservation->espace->nom);
    if ($myRoom) {
        $tcx = $myRoom['x'] + $myRoom['w'] / 2;
        $tcy = $myRoom['y'] + $myRoom['h'] / 2;
        $cor = $tcx > 350 ? 320 : 350;
        $hor = $tcy < 160 ? 165 : ($tcy > 310 ? 320 : 240);
        $myPathD = "M 350 525 L 350 320 L $cor 320 L $cor $hor L $tcx $hor L $tcx $tcy";
    }
}
@endphp

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="fw-bold mb-1"><i class="fas fa-drafting-compass me-3"></i>Plan d'architecte interactif</h1>
        <p class="mb-0 opacity-75">CoWork Tunisie · Berges du Lac 2 · Vue temps réel des salles</p>
    </div>
</div>

<div class="container pb-5">

    @if($myReservation)
    <div class="alert border-0 shadow-sm mb-4 d-flex align-items-center" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
        <div class="me-3" style="font-size: 2.2rem;">🎯</div>
        <div class="flex-grow-1">
            <h5 class="mb-1 fw-bold text-primary">Bonjour {{ auth('client')->user()->nom ?? '' }} ! Votre salle vous attend</h5>
            <div class="text-dark">
                <strong>{{ $myReservation->espace->nom }}</strong> ·
                de {{ \Carbon\Carbon::parse($myReservation->heure_debut)->format('H:i') }}
                à {{ \Carbon\Carbon::parse($myReservation->heure_fin)->format('H:i') }}
                · Suivez le <span class="text-primary fw-bold">point bleu animé</span> ↓
            </div>
        </div>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-list"></i> Mes réservations
        </a>
    </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);"><div style="font-size:2rem;">🟢</div><div class="fw-bold fs-2 text-success counter" data-target="{{ $counts['libre'] }}">0</div><div class="small fw-semibold">Disponibles</div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center" style="background:linear-gradient(135deg,#fef3c7,#fde68a);"><div style="font-size:2rem;">🟡</div><div class="fw-bold fs-2 text-warning counter" data-target="{{ $counts['bientot'] }}">0</div><div class="small fw-semibold">Bientôt occupées</div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center" style="background:linear-gradient(135deg,#fee2e2,#fecaca);"><div style="font-size:2rem;">🔴</div><div class="fw-bold fs-2 text-danger counter" data-target="{{ $counts['occupe'] }}">0</div><div class="small fw-semibold">Occupées</div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center" style="background:linear-gradient(135deg,#e2e8f0,#cbd5e1);"><div style="font-size:2rem;">⚫</div><div class="fw-bold fs-2 text-secondary counter" data-target="{{ $counts['indispo'] }}">0</div><div class="small fw-semibold">Indisponibles</div></div></div>
    </div>

    <div class="card shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold mb-0"><i class="fas fa-building me-2 text-primary"></i>Étage 1 — Plan d'architecte</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="view-2d"><i class="fas fa-square me-1"></i>Vue 2D</button>
                    <button class="btn btn-outline-primary" id="view-3d"><i class="fas fa-cube me-1"></i>Vue 3D</button>
                </div>
                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ now()->format('H:i') }}</small>
            </div>
        </div>

        <div id="plan-wrapper" style="background:#f8fafc; border-radius:12px; padding:30px; overflow:hidden; perspective:1800px;">
            <div id="plan-container" class="plan-2d-mode">
                <svg viewBox="0 0 700 560" style="width:100%; height:auto; font-family:'Segoe UI',sans-serif; max-height:78vh;">
                    <defs>
                        <pattern id="dottedFloor" width="14" height="14" patternUnits="userSpaceOnUse">
                            <circle cx="7" cy="7" r="0.7" fill="#cbd5e1"/>
                        </pattern>
                        <filter id="planShadow" x="-10%" y="-10%" width="120%" height="120%">
                            <feGaussianBlur in="SourceAlpha" stdDeviation="2"/>
                            <feOffset dx="1" dy="2"/>
                            <feComponentTransfer><feFuncA type="linear" slope="0.2"/></feComponentTransfer>
                            <feMerge><feMergeNode/><feMergeNode in="SourceGraphic"/></feMerge>
                        </filter>
                        <filter id="glowBlue" x="-50%" y="-50%" width="200%" height="200%">
                            <feGaussianBlur stdDeviation="3" result="b"/>
                            <feFlood flood-color="#2563eb" flood-opacity="0.7"/>
                            <feComposite in2="b" operator="in"/>
                            <feMerge><feMergeNode/><feMergeNode in="SourceGraphic"/></feMerge>
                        </filter>
                    </defs>

                    {{-- Sol blanc avec motif --}}
                    <rect x="10" y="10" width="680" height="490" fill="white"/>
                    <rect x="10" y="10" width="680" height="490" fill="url(#dottedFloor)"/>

                    {{-- Murs extérieurs (épais et noirs) --}}
                    <rect x="10" y="10" width="680" height="490" fill="none" stroke="#0f172a" stroke-width="6" rx="2"/>

                    @foreach($layout as $room)
                        @php
                            $espace = $espacesByName[$room['name']] ?? null;
                            $status = $getStatus($espace);
                            $cx = $room['x'] + $room['w'] / 2;
                            $cy = $room['y'] + $room['h'] / 2;
                            $isMine = $espace && $myEspaceId === $espace->id;
                            $shortName = trim(explode('–', $room['name'])[1] ?? $room['name']);
                            $statusOpacity = $isMine ? 0.45 : 0.15;
                        @endphp

                        <g class="room-group" style="cursor:pointer;"
                           @if($espace) onclick="window.location='{{ route('espaces.show', $espace->id) }}'" @endif>

                            {{-- Sol de la salle (blanc) --}}
                            <rect x="{{ $room['x'] }}" y="{{ $room['y'] }}" width="{{ $room['w'] }}" height="{{ $room['h'] }}"
                                  fill="white" stroke="none"/>

                            {{-- Overlay statut (subtil) --}}
                            <rect x="{{ $room['x'] }}" y="{{ $room['y'] }}" width="{{ $room['w'] }}" height="{{ $room['h'] }}"
                                  fill="{{ $status['fill'] }}" fill-opacity="{{ $statusOpacity }}" stroke="none">
                                <title>{{ $room['name'] }}{{ $espace ? ' — ' . $espace->prix_heure . ' TND/h — ' . $status['label'] : '' }}</title>
                            </rect>

                            {{-- Murs de la salle (noirs épais) --}}
                            <rect x="{{ $room['x'] }}" y="{{ $room['y'] }}" width="{{ $room['w'] }}" height="{{ $room['h'] }}"
                                  fill="none" stroke="#0f172a" stroke-width="{{ $isMine ? 4 : 3 }}"/>

                            {{-- Porte (gap blanc + arc d'ouverture) --}}
                            @php $door = $room['door']; @endphp
                            @if($door['side'] === 'bottom')
                                <rect x="{{ $door['x']-9 }}" y="{{ $door['y']-3 }}" width="18" height="6" fill="white"/>
                                <path d="M {{ $door['x']-9 }} {{ $door['y'] }} A 18 18 0 0 1 {{ $door['x']+9 }} {{ $door['y']-18 }}" fill="none" stroke="#475569" stroke-width="0.8"/>
                                <line x1="{{ $door['x']-9 }}" y1="{{ $door['y'] }}" x2="{{ $door['x']-9 }}" y2="{{ $door['y']-18 }}" stroke="#475569" stroke-width="1.5"/>
                            @elseif($door['side'] === 'top')
                                <rect x="{{ $door['x']-9 }}" y="{{ $door['y']-3 }}" width="18" height="6" fill="white"/>
                                <path d="M {{ $door['x']-9 }} {{ $door['y'] }} A 18 18 0 0 0 {{ $door['x']+9 }} {{ $door['y']+18 }}" fill="none" stroke="#475569" stroke-width="0.8"/>
                                <line x1="{{ $door['x']-9 }}" y1="{{ $door['y'] }}" x2="{{ $door['x']-9 }}" y2="{{ $door['y']+18 }}" stroke="#475569" stroke-width="1.5"/>
                            @elseif($door['side'] === 'left')
                                <rect x="{{ $door['x']-3 }}" y="{{ $door['y']-9 }}" width="6" height="18" fill="white"/>
                                <path d="M {{ $door['x'] }} {{ $door['y']-9 }} A 18 18 0 0 1 {{ $door['x']+18 }} {{ $door['y']+9 }}" fill="none" stroke="#475569" stroke-width="0.8"/>
                                <line x1="{{ $door['x'] }}" y1="{{ $door['y']-9 }}" x2="{{ $door['x']+18 }}" y2="{{ $door['y']-9 }}" stroke="#475569" stroke-width="1.5"/>
                            @endif

                            {{-- MOBILIER (style architecte : blanc + contour fin) --}}
                            <g stroke="#1e293b" fill="white">
                            @if($room['type'] === 'open')
                                @php
                                    $deskW = 30; $deskH = 14; $gapX = 18; $gapY = 22;
                                    $totalW = $room['cols']*$deskW + ($room['cols']-1)*$gapX;
                                    $totalH = $room['rows']*$deskH + ($room['rows']-1)*$gapY;
                                    $offX = $room['x'] + ($room['w'] - $totalW)/2;
                                    $offY = $room['y'] + ($room['h'] - $totalH)/2 + 4;
                                @endphp
                                @for($r = 0; $r < $room['rows']; $r++)
                                    @for($c = 0; $c < $room['cols']; $c++)
                                        @php $dx = $offX + $c*($deskW+$gapX); $dy = $offY + $r*($deskH+$gapY); @endphp
                                        <rect x="{{ $dx }}" y="{{ $dy }}" width="{{ $deskW }}" height="{{ $deskH }}" stroke-width="1"/>
                                        <path d="M {{ $dx+$deskW/2-5 }} {{ $dy+$deskH+2 }} a 5 5 0 0 0 10 0" stroke-width="1"/>
                                        <line x1="{{ $dx+$deskW/2-5 }}" y1="{{ $dy+$deskH+2 }}" x2="{{ $dx+$deskW/2+5 }}" y2="{{ $dy+$deskH+2 }}" stroke-width="1"/>
                                    @endfor
                                @endfor

                            @elseif($room['type'] === 'cluster')
                                @for($cr = 0; $cr < 2; $cr++)
                                    @for($cc = 0; $cc < 2; $cc++)
                                        @php
                                            $clCx = $room['x'] + 75 + $cc*150;
                                            $clCy = $room['y'] + 50 + $cr*55;
                                        @endphp
                                        <circle cx="{{ $clCx }}" cy="{{ $clCy }}" r="18" stroke-width="1.2"/>
                                        @for($a = 0; $a < 5; $a++)
                                            @php
                                                $ang = $a * (2*pi()/5) - pi()/2;
                                                $px = $clCx + cos($ang)*26;
                                                $py = $clCy + sin($ang)*22;
                                            @endphp
                                            <circle cx="{{ $px }}" cy="{{ $py }}" r="4" stroke-width="1"/>
                                        @endfor
                                    @endfor
                                @endfor

                            @elseif($room['type'] === 'meeting')
                                @php
                                    $tableW = $room['w'] * 0.55;
                                    $tableH = $room['h'] * 0.32;
                                    $tx = $cx - $tableW/2;
                                    $ty = $cy - $tableH/2 + 2;
                                @endphp
                                <rect x="{{ $tx }}" y="{{ $ty }}" width="{{ $tableW }}" height="{{ $tableH }}" stroke-width="1.5" rx="3"/>
                                @php
                                    $half = ceil($room['chairs'] / 2);
                                    $step = $tableW / ($half + 1);
                                @endphp
                                @for($i = 1; $i <= $half; $i++)
                                    <path d="M {{ $tx+$i*$step-5 }} {{ $ty-3 }} a 5 5 0 0 1 10 0 z" stroke-width="1"/>
                                    @if($i + $half <= $room['chairs'])
                                    <path d="M {{ $tx+$i*$step-5 }} {{ $ty+$tableH+3 }} a 5 5 0 0 0 10 0 z" stroke-width="1"/>
                                    @endif
                                @endfor

                            @elseif($room['type'] === 'office')
                                @for($i = 0; $i < $room['chairs']; $i++)
                                    @php
                                        $bx = $room['x'] + 18 + $i * (($room['w']-44)/max($room['chairs'],1));
                                        $by = $cy - 8;
                                    @endphp
                                    <rect x="{{ $bx }}" y="{{ $by }}" width="38" height="18" stroke-width="1"/>
                                    <path d="M {{ $bx+14 }} {{ $by+22 }} a 5 5 0 0 0 10 0" stroke-width="1"/>
                                    <line x1="{{ $bx+14 }}" y1="{{ $by+22 }}" x2="{{ $bx+24 }}" y2="{{ $by+22 }}" stroke-width="1"/>
                                @endfor
                                {{-- Caisson latéral --}}
                                <rect x="{{ $room['x']+$room['w']-25 }}" y="{{ $room['y']+10 }}" width="15" height="22" stroke-width="1"/>
                                <line x1="{{ $room['x']+$room['w']-25 }}" y1="{{ $room['y']+21 }}" x2="{{ $room['x']+$room['w']-10 }}" y2="{{ $room['y']+21 }}" stroke-width="0.6"/>

                            @elseif($room['type'] === 'booth')
                                <rect x="{{ $cx-13 }}" y="{{ $cy-5 }}" width="26" height="11" stroke-width="1"/>
                                <path d="M {{ $cx-5 }} {{ $cy+13 }} a 5 5 0 0 0 10 0" stroke-width="1"/>
                                <line x1="{{ $cx-5 }}" y1="{{ $cy+13 }}" x2="{{ $cx+5 }}" y2="{{ $cy+13 }}" stroke-width="1"/>
                                {{-- CORRECTION 1 : emoji 📞 affiché une seule fois (était répété 3 fois) --}}
                                <text x="{{ $cx }}" y="{{ $cy-10 }}" text-anchor="middle" font-size="8" fill="#475569" stroke="none">📞</text>

                            @elseif($room['type'] === 'studio')
                                {{-- Caméra --}}
                                <circle cx="{{ $room['x']+30 }}" cy="{{ $room['y']+50 }}" r="10" stroke-width="1.2"/>
                                <circle cx="{{ $room['x']+30 }}" cy="{{ $room['y']+50 }}" r="5" stroke-width="0.8"/>
                                {{-- Fond vert --}}
                                <rect x="{{ $room['x']+55 }}" y="{{ $room['y']+30 }}" width="85" height="60" stroke-width="1.5" stroke-dasharray="3 2"/>
                                <text x="{{ $room['x']+97 }}" y="{{ $room['y']+65 }}" text-anchor="middle" font-size="10" fill="#475569" stroke="none">FOND VERT</text>
                                {{-- Lampes --}}
                                <circle cx="{{ $room['x']+30 }}" cy="{{ $room['y']+105 }}" r="6" stroke-width="1"/>
                                <circle cx="{{ $room['x']+80 }}" cy="{{ $room['y']+115 }}" r="6" stroke-width="1"/>
                                <circle cx="{{ $room['x']+130 }}" cy="{{ $room['y']+105 }}" r="6" stroke-width="1"/>

                            @elseif($room['type'] === 'lounge')
                                {{-- Sofas (rectangles arrondis) --}}
                                <rect x="{{ $room['x']+20 }}" y="{{ $room['y']+30 }}" width="85" height="22" rx="8" stroke-width="1.2"/>
                                <line x1="{{ $room['x']+45 }}" y1="{{ $room['y']+34 }}" x2="{{ $room['x']+45 }}" y2="{{ $room['y']+48 }}" stroke-width="0.6"/>
                                <line x1="{{ $room['x']+80 }}" y1="{{ $room['y']+34 }}" x2="{{ $room['x']+80 }}" y2="{{ $room['y']+48 }}" stroke-width="0.6"/>
                                <rect x="{{ $room['x']+135 }}" y="{{ $room['y']+30 }}" width="85" height="22" rx="8" stroke-width="1.2"/>
                                <line x1="{{ $room['x']+160 }}" y1="{{ $room['y']+34 }}" x2="{{ $room['x']+160 }}" y2="{{ $room['y']+48 }}" stroke-width="0.6"/>
                                <line x1="{{ $room['x']+195 }}" y1="{{ $room['y']+34 }}" x2="{{ $room['x']+195 }}" y2="{{ $room['y']+48 }}" stroke-width="0.6"/>
                                {{-- Tables basses --}}
                                <circle cx="{{ $room['x']+62 }}" cy="{{ $room['y']+85 }}" r="16" stroke-width="1.2"/>
                                <text x="{{ $room['x']+62 }}" y="{{ $room['y']+89 }}" text-anchor="middle" font-size="10" fill="#475569" stroke="none">☕</text>
                                <circle cx="{{ $room['x']+178 }}" cy="{{ $room['y']+85 }}" r="16" stroke-width="1.2"/>
                                <text x="{{ $room['x']+178 }}" y="{{ $room['y']+89 }}" text-anchor="middle" font-size="10" fill="#475569" stroke="none">☕</text>
                                {{-- Sofas bas --}}
                                <rect x="{{ $room['x']+20 }}" y="{{ $room['y']+125 }}" width="85" height="22" rx="8" stroke-width="1.2"/>
                                <line x1="{{ $room['x']+45 }}" y1="{{ $room['y']+129 }}" x2="{{ $room['x']+45 }}" y2="{{ $room['y']+143 }}" stroke-width="0.6"/>
                                <rect x="{{ $room['x']+135 }}" y="{{ $room['y']+125 }}" width="85" height="22" rx="8" stroke-width="1.2"/>
                                <line x1="{{ $room['x']+160 }}" y1="{{ $room['y']+129 }}" x2="{{ $room['x']+160 }}" y2="{{ $room['y']+143 }}" stroke-width="0.6"/>

                            @elseif($room['type'] === 'auditorium')
                                {{-- Scène --}}
                                <rect x="{{ $room['x']+25 }}" y="{{ $room['y']+18 }}" width="360" height="22" stroke-width="1.5"/>
                                <text x="{{ $cx }}" y="{{ $room['y']+33 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#1e293b" stroke="none">🎤 SCÈNE</text>
                                {{-- Sièges --}}
                                @for($r = 0; $r < 4; $r++)
                                    @for($c = 0; $c < 10; $c++)
                                        <rect x="{{ $room['x']+33 + $c*36 }}" y="{{ $room['y']+58 + $r*24 }}" width="20" height="13" rx="2" stroke-width="0.8"/>
                                        <line x1="{{ $room['x']+35 + $c*36 }}" y1="{{ $room['y']+58 + $r*24 }}" x2="{{ $room['x']+51 + $c*36 }}" y2="{{ $room['y']+58 + $r*24 }}" stroke-width="0.8"/>
                                    @endfor
                                @endfor
                            @endif
                            </g>

                            {{-- Étiquette de salle --}}
                            <rect x="{{ $room['x']+5 }}" y="{{ $room['y']+5 }}" width="{{ min(strlen($shortName)*5.5+10, $room['w']-10) }}" height="14" fill="white" fill-opacity="0.95" stroke="{{ $status['stroke'] }}" stroke-width="0.6" rx="2"/>
                            <text x="{{ $room['x']+10 }}" y="{{ $room['y']+15 }}" font-size="9" font-weight="700" fill="#1e293b" style="pointer-events:none;">{{ $shortName }}</text>

                            {{-- Marqueur "Ma salle" --}}
                            @if($isMine)
                                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="22" fill="none" stroke="#2563eb" stroke-width="3" opacity="0.7" filter="url(#glowBlue)">
                                    <animate attributeName="r" from="22" to="45" dur="1.5s" repeatCount="indefinite"/>
                                    <animate attributeName="opacity" from="0.7" to="0" dur="1.5s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="14" fill="#2563eb" stroke="white" stroke-width="2"/>
                                <text x="{{ $cx }}" y="{{ $cy + 5 }}" text-anchor="middle" fill="white" font-size="16" font-weight="700">📍</text>
                                @if($myReservation && $myReservation->numero_siege)
                                <text x="{{ $cx }}" y="{{ $cy - 28 }}" text-anchor="middle" fill="#1e40af" font-size="11" font-weight="700" stroke="white" stroke-width="3" paint-order="stroke">
                                    Bureau n°{{ $myReservation->numero_siege }}
                                </text>
                                @endif
                            @endif
                        </g>
                    @endforeach

                    {{-- Icônes WC et services --}}
                    {{-- CORRECTION 2 : suppression du rect avec height="-3" invalide en SVG --}}
                    <g fill="#475569" stroke="none" font-size="11" text-anchor="middle">
                        <text x="50" y="505" font-size="9">🚻 WC</text>
                        <text x="690" y="505" font-size="9">🚻 WC</text>
                    </g>

                    {{-- Chemin GPS --}}
                    @if($myPathD)
                    <path id="myPath" d="{{ $myPathD }}"
                          stroke="#2563eb" stroke-width="4" stroke-dasharray="10 6" fill="none" opacity="0.85"
                          stroke-linecap="round" filter="url(#glowBlue)">
                        <animate attributeName="stroke-dashoffset" from="0" to="-32" dur="0.8s" repeatCount="indefinite"/>
                    </path>
                    <circle r="7" fill="#2563eb" stroke="white" stroke-width="2.5">
                        <animateMotion dur="4s" repeatCount="indefinite">
                            <mpath href="#myPath"/>
                        </animateMotion>
                    </circle>
                    @endif

                    {{-- Entrée principale --}}
                    <rect x="320" y="497" width="60" height="6" fill="white" stroke="none"/>
                    <path d="M 320 497 A 60 60 0 0 1 380 497" fill="none" stroke="#475569" stroke-width="0.8"/>
                    <text x="350" y="525" text-anchor="middle" fill="#0f172a" font-size="11" font-weight="700">
                        ▼ Entrée principale ▼
                        <animate attributeName="opacity" values="1;0.5;1" dur="2s" repeatCount="indefinite"/>
                    </text>

                    {{-- Boussole --}}
                    <g transform="translate(650, 45)">
                        <circle r="22" fill="white" stroke="#0f172a" stroke-width="1.5"/>
                        <polygon points="0,-16 -5,2 0,-2 5,2" fill="#dc2626"/>
                        <polygon points="0,16 -5,-2 0,2 5,-2" fill="#475569"/>
                        <text y="-7" text-anchor="middle" font-size="9" font-weight="700" fill="#dc2626">N</text>
                    </g>

                    {{-- Échelle --}}
                    <g transform="translate(30, 530)">
                        <line x1="0" y1="0" x2="60" y2="0" stroke="#0f172a" stroke-width="1.5"/>
                        <line x1="0" y1="-3" x2="0" y2="3" stroke="#0f172a" stroke-width="1.5"/>
                        <line x1="60" y1="-3" x2="60" y2="3" stroke="#0f172a" stroke-width="1.5"/>
                        <text x="30" y="-6" text-anchor="middle" font-size="9" fill="#475569">5 m</text>
                    </g>
                </svg>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 mt-3 small text-muted">
            <span><span class="d-inline-block" style="width:14px;height:14px;background:#22c55e;opacity:0.5;border:1px solid #15803d;border-radius:2px;"></span> Disponible</span>
            <span><span class="d-inline-block" style="width:14px;height:14px;background:#f59e0b;opacity:0.5;border:1px solid #b45309;border-radius:2px;"></span> Bientôt</span>
            <span><span class="d-inline-block" style="width:14px;height:14px;background:#ef4444;opacity:0.5;border:1px solid #b91c1c;border-radius:2px;"></span> Occupée</span>
            <span><span class="d-inline-block" style="width:14px;height:14px;background:#94a3b8;opacity:0.5;border:1px solid #475569;border-radius:2px;"></span> Indisponible</span>
            @if($myReservation)
            <span><span class="d-inline-block" style="width:14px;height:14px;background:#2563eb;border-radius:50%;"></span> Votre salle</span>
            @endif
        </div>

        <p class="text-muted small mt-3 mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Cliquez sur une salle pour la réserver · Bascule entre <strong>Vue 2D</strong> et <strong>Vue 3D</strong> · Mise à jour automatique
        </p>
    </div>
</div>

<style>
.room-group:hover rect:nth-of-type(2) {
    fill-opacity: 0.35 !important;
}
.room-group:hover rect:nth-of-type(3) {
    stroke-width: 4 !important;
}

#plan-container {
    transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center center;
}

#plan-container.plan-3d-mode {
    transform: rotateX(50deg) rotateZ(-12deg) scale(0.92);
}

#plan-container.plan-3d-mode svg {
    filter: drop-shadow(0 30px 40px rgba(0,0,0,0.3));
}
</style>

<script>
const view2d = document.getElementById('view-2d');
const view3d = document.getElementById('view-3d');
const container = document.getElementById('plan-container');

view2d.addEventListener('click', () => {
    container.classList.remove('plan-3d-mode');
    view2d.classList.add('btn-primary'); view2d.classList.remove('btn-outline-primary');
    view3d.classList.add('btn-outline-primary'); view3d.classList.remove('btn-primary');
});

view3d.addEventListener('click', () => {
    container.classList.add('plan-3d-mode');
    view3d.classList.add('btn-primary'); view3d.classList.remove('btn-outline-primary');
    view2d.classList.add('btn-outline-primary'); view2d.classList.remove('btn-primary');
});

// Animation des compteurs
document.querySelectorAll('.counter').forEach(counter => {
    const target = parseInt(counter.getAttribute('data-target'));
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        counter.textContent = Math.floor(current);
    }, 20);
});

// Rechargement automatique toutes les 60 secondes pour mise à jour temps réel
setTimeout(() => location.reload(), 60000);
</script>
@endsection