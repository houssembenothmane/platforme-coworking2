@extends('layouts.app')
@section('title', 'Mes réservations')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="fw-bold mb-1"><i class="fas fa-calendar-check me-3"></i>Mes réservations</h1>
        <p class="mb-0 opacity-75">Gérez vos réservations d'espaces</p>
    </div>
</div>

<div class="container pb-5">
    @if($reservations->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <p class="text-muted">Vous n'avez aucune réservation.</p>
            <a href="{{ route('espaces.index') }}" class="btn btn-primary">Réserver un espace</a>
        </div>
    @else
    <div class="row g-4">
        @foreach($reservations as $reservation)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0">{{ $reservation->espace->nom }}</h5>
                        <span class="badge {{ $reservation->statut == 'Confirmée' ? 'bg-success' : ($reservation->statut == 'En attente' ? 'bg-warning' : 'bg-danger') }}">
                            {{ $reservation->statut }}
                        </span>
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-calendar me-1"></i>{{ $reservation->date->format('d/m/Y') }}<br>
                        <i class="fas fa-clock me-1"></i>{{ $reservation->heure_debut }} - {{ $reservation->heure_fin }}
                    </p>
                    <div class="mb-3">
                        <strong>{{ number_format($reservation->montant, 2) }} TND</strong>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-eye me-1"></i>Détails
                        </a>
                        @if($reservation->statut == 'En attente')
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="flex-fill">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100" onclick="return confirm('Annuler cette réservation ?')">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                        </form>
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