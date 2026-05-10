@extends('layouts.app')
@section('title', 'Gestion des Réservations')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-calendar-check me-2"></i>Gestion des Réservations</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Espace</th>
                        <th>Date</th>
                        <th>Horaire</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    @php
                        $colors = [
                            'Confirmée'  => 'success',
                            'En attente' => 'warning',
                            'Annulée'    => 'danger',
                        ];
                        $color = $colors[$reservation->statut] ?? 'secondary';
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $reservation->id }}</td>
                        <td>
                            <strong>{{ $reservation->client->nom ?? '—' }}</strong>
                            <div class="text-muted small">{{ $reservation->client->email ?? '' }}</div>
                        </td>
                        <td>{{ $reservation->espace->nom ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                        <td class="small text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $reservation->heure_debut }} → {{ $reservation->heure_fin }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $color }}">{{ $reservation->statut }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                <!-- Changer statut -->
                                <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="statut" class="form-select form-select-sm w-auto"
                                            onchange="this.form.submit()">
                                        <option value="En attente" {{ $reservation->statut=='En attente' ? 'selected':'' }}>En attente</option>
                                        <option value="Confirmée"  {{ $reservation->statut=='Confirmée'  ? 'selected':'' }}>Confirmée</option>
                                        <option value="Annulée"    {{ $reservation->statut=='Annulée'    ? 'selected':'' }}>Annulée</option>
                                    </select>
                                </form>
                                <!-- Supprimer -->
                                <form action="{{ route('admin.reservations.destroy', $reservation->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cette réservation ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucune réservation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection