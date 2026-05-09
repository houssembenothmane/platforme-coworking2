@extends('layouts.app')
@section('title', 'Laisser un avis')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Laisser un avis pour {{ $espace->nom }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('avis.store') }}">
                        @csrf
                        <input type="hidden" name="espace_id" value="{{ $espace->id }}">
                        <div class="mb-3">
                            <label class="form-label">Note *</label>
                            <div class="rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="note" value="{{ $i }}" {{ old('note') == $i ? 'checked' : '' }}>
                                <label for="star{{ $i }}" title="{{ $i }} star">☆</label>
                                @endfor
                            </div>
                            @error('note')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="4" placeholder="Partagez votre expérience...">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Publier l'avis
                            </button>
                            <a href="{{ route('espaces.show', $espace->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating input {
    display: none;
}
.rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #f59e0b;
}
</style>
@endsection
