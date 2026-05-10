@extends('layouts.app')
@section('title', 'Gestion des Clients')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-users me-2"></i>Gestion des Clients</h2>

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
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Réservations</th>
                        <th>Avis</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td class="text-muted small">{{ $client->id }}</td>
                        <td><strong>{{ $client->nom }}</strong></td>
                        <td>{{ $client->email }}</td>
                        <td><span class="badge bg-primary rounded-pill">{{ $client->reservations_count }}</span></td>
                        <td><span class="badge bg-warning text-dark rounded-pill">{{ $client->avis_count }}</span></td>
                        <td class="text-muted small">{{ $client->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.clients.destroy', $client->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce client et toutes ses données ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucun client.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection