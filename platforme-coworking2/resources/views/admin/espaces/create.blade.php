@extends('layouts.app')
@section('title', 'Créer un Espace')

@section('content')
<div class="container py-5" style="max-width:680px">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.espaces.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="mb-0"><i class="fas fa-plus me-2"></i>Nouvel Espace</h2>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card p-4">
        <form action="{{ route('admin.espaces.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}" required>
                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3" placeholder="Décrivez l'espace...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prix / heure (TND) <span class="text-danger">*</span></label>
                    <input type="number" name="prix_heure" step="0.01" min="1"
                           class="form-control @error('prix_heure') is-invalid @enderror"
                           value="{{ old('prix_heure') }}" required>
                    @error('prix_heure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Capacité (personnes) <span class="text-danger">*</span></label>
                    <input type="number" name="capacite" min="1"
                           class="form-control @error('capacite') is-invalid @enderror"
                           value="{{ old('capacite') }}" required>
                    @error('capacite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Statut <span class="text-danger">*</span></label>
                <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                    <option value="Disponible"   {{ old('statut','Disponible')=='Disponible'   ? 'selected' : '' }}>Disponible</option>
                    <option value="Indisponible" {{ old('statut')=='Indisponible' ? 'selected' : '' }}>Indisponible</option>
                </select>
                @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Latitude</label>
                    <input type="number" name="latitude" step="any"
                           class="form-control @error('latitude') is-invalid @enderror"
                           value="{{ old('latitude') }}" placeholder="ex: 36.8420">
                    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Longitude</label>
                    <input type="number" name="longitude" step="any"
                           class="form-control @error('longitude') is-invalid @enderror"
                           value="{{ old('longitude') }}" placeholder="ex: 10.2680">
                    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Enregistrer
                </button>
                <a href="{{ route('admin.espaces.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection