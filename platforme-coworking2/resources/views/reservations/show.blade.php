@extends('layouts.app')
@section('title', 'Détails de la réservation')

@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="background:none;padding:0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reservations.index') }}" style="color:rgba(255,255,255,.8)">Mes réservations</a>
                </li>
                <li class="breadcrumb-item active text-white">
                    Réservation #{{ $reservation->IdReservation }}
                </li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1">
            <i class="fas fa-receipt me-3"></i>Réservation #{{ $reservation->IdReservation }}
        </h1>
        <p class="mb-0 opacity-75">Détails et facture de votre réservation</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Détails de la réservation</h5>
                    <span class="badge {{
                        $reservation->statut == 'Confirmée'  ? 'bg-success'   :
                        ($reservation->statut == 'En attente' ? 'bg-warning'  :
                        ($reservation->statut == 'Terminée'  ? 'bg-secondary' : 'bg-danger'))
                    }} fs-6">
                        {{ $reservation->statut }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Espace :</strong><br>{{ $reservation->espace->nom }}
                        </div>
                        <div class="col-md-6">
                            <strong>Date :</strong><br>{{ $reservation->date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Heure début :</strong><br>{{ $reservation->heure_debut }}
                        </div>
                        <div class="col-md-6">
                            <strong>Heure fin :</strong><br>{{ $reservation->heure_fin }}
                        </div>
                        @if($reservation->numero_siege)
                        <div class="col-md-6">
                            <strong>Bureau n° :</strong><br>{{ $reservation->numero_siege }}
                        </div>
                        @endif
                        <div class="col-md-6">
                            <strong>Montant :</strong><br>
                            <span class="fw-bold text-primary">{{ number_format($reservation->montant, 2) }} TND</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Créée le :</strong><br>{{ $reservation->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-3 d-flex gap-3">
                <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                @if($reservation->statut === 'Confirmée' || $reservation->statut === 'En attente')
                <form action="{{ route('reservations.destroy', $reservation->IdReservation) }}"
                      method="POST"
                      onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Annuler la réservation
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Facture</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h6>CoWork Tunisie</h6>
                        <small class="text-muted">Plateforme de réservation</small>
                    </div>
                    <hr>
                    <p><strong>Facture N° :</strong> {{ $facture['numero'] }}</p>
                    <p><strong>Client :</strong> {{ $facture['client'] }}</p>
                    <p><strong>Espace :</strong> {{ $facture['espace'] }}</p>
                    <p><strong>Date :</strong> {{ $facture['date'] }}</p>
                    <p><strong>Horaires :</strong> {{ $facture['heure_debut'] }} - {{ $facture['heure_fin'] }}</p>
                    <p><strong>Statut :</strong> {{ $facture['statut'] }}</p>
                    <hr>
                    <h5 class="text-end">Total : {{ $facture['montant'] }}</h5>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('reservations.create', $reservation->espace->IdEspace) }}"
                    class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="fas fa-calendar-plus me-2"></i>Nouvelle réservation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection