@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
    </h2>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h3>{{ $totalClients }}</h3>
                    <p class="mb-0"><i class="fas fa-users me-2"></i>Clients</p>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.clients.index') }}" class="text-white">Voir tous</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h3>{{ $totalReservations }}</h3>
                    <p class="mb-0"><i class="fas fa-calendar-check me-2"></i>Réservations</p>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.reservations.index') }}" class="text-white">Voir toutes</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h3>{{ $totalEspaces }}</h3>
                    <p class="mb-0"><i class="fas fa-building me-2"></i>Espaces</p>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.espaces.index') }}" class="text-white">Voir tous</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h3>{{ $totalAvis }}</h3>
                    <p class="mb-0"><i class="fas fa-star me-2"></i>Avis</p>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.avis.index') }}" class="text-white">Voir tous</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection