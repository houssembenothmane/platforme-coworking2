@extends('layouts.app')
@section('title', 'Modifier ' . $espace->nom)

@section('content')
<div class="container py-5" style="max-width:680px">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.espaces.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier : {{ $espace->nom }}</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card p-4">
        <form action="{{ route('admin.espaces.update', $espace->IdEspace) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom', $espace->nom) }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $espace->description) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prix / heure (TND) <span class="text-danger">*</span></label>
                    <input type="number" name="prix_heure" step="0.01" min="1"
                           class="form-control @error('prix_heure') is-invalid @enderror"
                           value="{{ old('prix_heure', $espace->prix_heure) }}" required>
                    @error('prix_heure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Capacité (personnes) <span class="text-danger">*</span></label>
                    <input type="number" name="capacite" min="1"
                           class="form-control @error('capacite') is-invalid @enderror"
                           value="{{ old('capacite', $espace->capacite) }}" required>
                    @error('capacite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                <select name="statut" class="form-select" required>
                    <option value="Disponible"   {{ old('statut',$espace->statut)=='Disponible'   ? 'selected' : '' }}>Disponible</option>
                    <option value="Indisponible" {{ old('statut',$espace->statut)=='Indisponible' ? 'selected' : '' }}>Indisponible</option>
                </select>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Latitude</label>
                    <input type="number" name="latitude" step="any"
                           class="form-control" value="{{ old('latitude', $espace->latitude) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Longitude</label>
                    <input type="number" name="longitude" step="any"
                           class="form-control" value="{{ old('longitude', $espace->longitude) }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Mettre à jour
                </button>
                <a href="{{ route('admin.espaces.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
