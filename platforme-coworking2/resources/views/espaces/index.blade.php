@extends('layouts.app')
@section('title', 'Espaces de coworking')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="fw-bold mb-1"><i class="fas fa-building me-3"></i>Nos salles disponibles</h1>
        <p class="mb-0 opacity-75">CoWork Tunisie · Berges du Lac 2 · Réservez votre salle à l'heure</p>
    </div>
</div>

<div class="container pb-5">

    <!-- Filtres -->
    <div class="card mb-4 p-3">
        <form method="GET" action="{{ route('espaces.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom de l'espace..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="Disponible" {{ request('statut')=='Disponible'?'selected':'' }}>Disponible</option>
                    <option value="Indisponible" {{ request('statut')=='Indisponible'?'selected':'' }}>Indisponible</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Prix max (TND/h)</label>
                <input type="number" name="prix_max" class="form-control" placeholder="Ex: 50" value="{{ request('prix_max') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrer</button>
            </div>
        </form>
    </div>

    <!-- Carte Google Maps -->
    <div class="card mb-4 overflow-hidden">
        <div class="card-header fw-semibold" style="background:#1a56db;color:#fff">
            <i class="fas fa-map-marker-alt me-2"></i>Localisation du coworking        </div>
        <div id="map" style="height:350px;width:100%"></div>
    </div>

    <!-- Liste des espaces -->
    @if($espaces->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-building fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucun espace trouvé avec ces critères.</p>
            <a href="{{ route('espaces.index') }}" class="btn btn-outline-primary">Voir tous les espaces</a>
        </div>
    @else
    <div class="row g-4">
        @foreach($espaces as $espace)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0">{{ $espace->nom }}</h5>
                        <span class="{{ $espace->statut=='Disponible' ? 'badge-disponible' : 'badge-indisponible' }}">
                            {{ $espace->statut }}
                        </span>
                    </div>
                    <p class="text-muted small mb-3">{{ Str::limit($espace->description, 80) }}</p>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background:#f0f4ff">
                                <div class="fw-bold text-primary">{{ $espace->prix_heure }} TND</div>
                                <div class="text-muted" style="font-size:.75rem">par heure</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background:#f0fdf4">
                                <div class="fw-bold text-success">{{ $espace->capacite }}</div>
                                <div class="text-muted" style="font-size:.75rem">personnes max</div>
                            </div>
                        </div>
                    </div>
                    <!-- Note -->
                    @php $note = round($espace->avis_avg_note ?? 0, 1); @endphp
                    <div class="mb-3">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star {{ $i<=$note ? 'star' : 'star-empty' }}"></i>
                        @endfor
                        <span class="text-muted small ms-1">{{ $note }}/5 ({{ $espace->avis_count }} avis)</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('espaces.show', $espace->id) }}" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-eye me-1"></i>Détails
                        </a>
                        @if($espace->statut == 'Disponible')
                        <a href="{{ auth('client')->check() ? route('reservations.create', $espace->id) : route('login') }}" class="btn btn-primary flex-fill">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Coordonnées du coworking (Lac 2, Tunis)
    const coworkingLat = 36.8420;
    const coworkingLng = 10.2680;
   
    const map = L.map('map').setView([coworkingLat, coworkingLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Un seul marqueur pour le coworking
    L.marker([coworkingLat, coworkingLng]).addTo(map)
        .bindPopup(`
            <div style="font-family: sans-serif; min-width: 200px;">
                <h6 style="margin:0 0 8px; color:#1a56db;">
                    <i class="fas fa-building"></i> CoWork Tunisie
                </h6>
                <p style="margin:0 0 4px; font-size: 13px;">
                    📍 Berges du Lac 2, Tunis
                </p>
                <p style="margin:0; font-size: 13px; color:#666;">
                    {{ $espaces->total() }} salles disponibles
                </p>
                <a href="#liste-espaces" style="display:inline-block; margin-top:8px; color:#1a56db; text-decoration:none; font-weight:600;">
                    Voir toutes les salles ↓
                </a>
            </div>
        `)
        .openPopup();
});
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
