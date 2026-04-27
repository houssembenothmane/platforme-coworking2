@extends('layouts.app')
@section('title', $espace->nom)

<style>
    #map-detail {
        height: 200px;
        width: 100%;
        z-index: 1;
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>

@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="background:none;padding:0">
                <li class="breadcrumb-item"><a href="{{ route('espaces.index') }}" style="color:rgba(255,255,255,.8)">Espaces</a></li>
                <li class="breadcrumb-item active text-white">{{ $espace->nom }}</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1">{{ $espace->nom }}</h1>
        <span class="{{ $espace->statut=='Disponible' ? 'badge-disponible' : 'badge-indisponible' }}">
            {{ $espace->statut }}
        </span>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Description</h5>
                <p class="text-muted">{{ $espace->description ?: 'Aucune description disponible.' }}</p>
                <div class="row g-3 mt-2">
                    <div class="col-sm-4 text-center p-3 rounded" style="background:#f0f4ff">
                        <div class="fw-bold text-primary fs-4">{{ $espace->prix_heure }} TND</div>
                        <div class="text-muted small">par heure</div>
                    </div>
                    <div class="col-sm-4 text-center p-3 rounded" style="background:#f0fdf4">
                        <div class="fw-bold text-success fs-4">{{ $espace->capacite }}</div>
                        <div class="text-muted small">personnes max</div>
                    </div>
                    <div class="col-sm-4 text-center p-3 rounded" style="background:#fef3c7">
                        <div class="fw-bold text-warning fs-4">{{ $noteMoyenne }}/5</div>
                        <div class="text-muted small">note moyenne</div>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i>Avis clients ({{ $espace->avis->count() }})</h5>
                    @if(auth('client')->check())
                    <a href="{{ route('avis.create', $espace->id) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-plus me-1"></i>Laisser un avis
                    </a>
                    @endif
                </div>

                @forelse($espace->avis as $avis)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>{{ $avis->client->nom ?? 'Client' }}</strong>
                            <div class="mt-1">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fas fa-star {{ $i<=$avis->note ? 'star' : 'star-empty' }}"></i>
                                @endfor
                            </div>
                            @if($avis->sentiment)
                                @php
                                    $colors = ['positif' => 'success', 'neutre' => 'secondary', 'negatif' => 'danger'];
                                    $icons  = ['positif' => '😊', 'neutre' => '😐', 'negatif' => '😞'];
                                @endphp
                            <span class="badge bg-{{ $colors[$avis->sentiment] ?? 'secondary' }} ms-2">
                                {{ $icons[$avis->sentiment] }} {{ ucfirst($avis->sentiment) }}
                            </span>
                            @endif
                        </div>
                        <span class="text-muted small">{{ $avis->created_at->diffForHumans() }}</span>
                    </div>
                    @if($avis->commentaire)
                    <p class="text-muted mt-2 mb-0">{{ $avis->commentaire }}</p>
                    @endif
                </div>
                @empty
                <p class="text-muted text-center py-3">Aucun avis pour cet espace.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 mb-4" style="position:sticky;top:80px">
                <h5 class="fw-bold mb-3">Réserver cet espace</h5>
                @if($espace->statut == 'Disponible')
                    @if(auth('client')->check())
                    <a href="{{ route('reservations.create', $espace->id) }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-calendar-plus me-2"></i>Réserver maintenant
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour réserver
                    </a>
                    @endif
                    <p class="text-muted small text-center mb-0">
                        <i class="fas fa-info-circle me-1"></i>Prix : {{ $espace->prix_heure }} TND/heure
                    </p>
                @else
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-times-circle me-2"></i>Cet espace est indisponible
                    </div>
                @endif
            </div>

            <div class="card overflow-hidden">
                <div class="card-header fw-semibold" style="background:#1a56db;color:#fff;font-size:.9rem">
                    <i class="fas fa-map-marker-alt me-2"></i>Localisation
                </div>
                <div id="map-detail"></div> </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Coordonnées (Tunis par défaut si vide)
    const lat = {{ $espace->latitude ?? 36.8189 }};
    const lng = {{ $espace->longitude ?? 10.1658 }};
   
    // Initialisation
    const map = L.map('map-detail').setView([lat, lng], 15);

    // Fond de carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Marqueur
    L.marker([lat, lng]).addTo(map)
        .bindPopup("<b>{{ $espace->nom }}</b>")
        .openPopup();
});
</script>
@endsection