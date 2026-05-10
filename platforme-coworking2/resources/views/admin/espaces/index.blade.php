@extends('layouts.app')
@section('title', 'Gestion des Espaces')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building me-2"></i>Gestion des Espaces</h2>
        <a href="{{ route('admin.espaces.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Ajouter un espace
        </a>
    </div>

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
                        <th>Prix/h</th>
                        <th>Capacité</th>
                        <th>Statut</th>
                        <th>Réservations</th>
                        <th>Avis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($espaces as $espace)
                    <tr>
                        <td class="text-muted small">{{ $espace->getKey() }}</td>
                        <td>
                            <strong>{{ $espace->nom }}</strong>
                            @if($espace->description)
                                <div class="text-muted small">{{ Str::limit($espace->description, 50) }}</div>
                            @endif
                        </td>
                        <td>{{ $espace->prix_heure }} TND</td>
                        <td>{{ $espace->capacite }} pers.</td>
                        <td>
                            <span class="badge bg-{{ $espace->statut === 'Disponible' ? 'success' : 'secondary' }}">
                                {{ $espace->statut }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill">{{ $espace->reservations_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $espace->avis_count }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.espaces.edit', $espace->getKey()) }}"
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.espaces.destroy', $espace->getKey()) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer « {{ $espace->nom }} » ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Aucun espace enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection