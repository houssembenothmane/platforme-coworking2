@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
        </h2>
        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Cartes statistiques --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalClients }}</h3>
                    <p class="mb-0">
                        <i class="fas fa-users me-2"></i>Clients
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center">
                    <a href="{{ route('admin.clients.index') }}" class="text-white text-decoration-none">
                        Voir tous <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalReservations }}</h3>
                    <p class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Réservations
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center">
                    <a href="{{ route('admin.reservations.index') }}" class="text-white text-decoration-none">
                        Voir toutes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalEspaces }}</h3>
                    <p class="mb-0">
                        <i class="fas fa-building me-2"></i>Espaces
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center">
                    <a href="{{ route('admin.espaces.index') }}" class="text-white text-decoration-none">
                        Voir tous <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Réservations récentes --}}
    @if(isset($recentReservations) && $recentReservations->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Réservations récentes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Espace</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->client->nom ?? 'N/A' }}</td>
                                    <td>{{ $reservation->espace->nom ?? 'N/A' }}</td>
                                    <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->statut === 'confirmée' ? 'success' : 'warning' }}">
                                            {{ ucfirst($reservation->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection