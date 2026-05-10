@extends('layouts.app')
@section('title', 'Réserver un espace')

@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="background:none;padding:0">
                <li class="breadcrumb-item"><a href="{{ route('espaces.index') }}" style="color:rgba(255,255,255,.8)">Espaces</a></li>
                <li class="breadcrumb-item"><a href="{{ route('espaces.show', $espace->IdEspace) }}" style="color:rgba(255,255,255,.8)">{{ $espace->nom }}</a></li>
                <li class="breadcrumb-item active text-white">Réservation</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1"><i class="fas fa-calendar-plus me-3"></i>Réserver {{ $espace->nom }}</h1>
        <p class="mb-0 opacity-75">Remplissez les détails de votre réservation</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Détails de l'espace</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4 text-center p-3 rounded" style="background:#f0f4ff">
                            <div class="fw-bold text-primary fs-4">{{ $espace->prix_heure }} TND</div>
                            <div class="text-muted small">par heure</div>
                        </div>
                        <div class="col-sm-4 text-center p-3 rounded" style="background:#f0fdf4">
                            <div class="fw-bold text-success fs-4">{{ $espace->capacite }}</div>
                            <div class="text-muted small">personnes max</div>
                        </div>
                        <div class="col-sm-4 text-center p-3 rounded" style="background:#fef3c7">
                            <div class="fw-bold text-warning fs-4">{{ round($espace->avis->avg('note'), 1) }}/5</div>
                            <div class="text-muted small">note moyenne</div>
                        </div>
                    </div>
                    <p class="text-muted">{{ $espace->description ?: 'Aucune description disponible.' }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Formulaire de réservation</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf
                        <input type="hidden" name="espace_id" value="{{ $espace->IdEspace }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="heure_debut" class="form-label">Heure début *</label>
                                <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}" required>
                                @error('heure_debut')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="heure_fin" class="form-label">Heure fin *</label>
                                <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="{{ old('heure_fin') }}" required>
                                @error('heure_fin')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if($hasSeats)
<div class="mb-3" id="seat-picker-block">
    <label class="form-label fw-semibold">
        <i class="fas fa-chair me-1"></i>Choisissez votre bureau
        <span class="badge bg-warning text-dark ms-2">Important</span>
    </label>
    <div class="card p-3" style="background:#f8fafc;">
        <p class="small text-muted mb-2">
            🟢 Cliquez sur un bureau libre pour le réserver. Les bureaux gris sont déjà pris.
        </p>
        <div id="seat-grid" class="d-flex flex-wrap gap-2 justify-content-center"></div>
        <input type="hidden" name="numero_siege" id="numero_siege" required>
        <div class="mt-2 text-center">
            <span class="text-primary fw-bold" id="selected-seat-label">Aucun bureau sélectionné</span>
        </div>
    </div>
</div>

<script>
const espaceId = {{ $espace->IdEspace }};
const totalSeats = {{ $espace->capacite }};
const dateInput = document.querySelector('input[name="date"]');
const hdInput = document.querySelector('input[name="heure_debut"]');
const hfInput = document.querySelector('input[name="heure_fin"]');

async function loadSeats() {
    if (!dateInput.value || !hdInput.value || !hfInput.value) return;
   
    const params = new URLSearchParams({
        espace_id: espaceId,
        date: dateInput.value,
        heure_debut: hdInput.value,
        heure_fin: hfInput.value
    });
   
    const res = await fetch('{{ url("/api/seats-occupied") }}?' + params);
    const data = await res.json();
    const occupied = data.occupied || [];
   
    const grid = document.getElementById('seat-grid');
    grid.innerHTML = '';
   
    for (let i = 1; i <= totalSeats; i++) {
        const isOccupied = occupied.includes(i);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn ' + (isOccupied ? 'btn-secondary' : 'btn-outline-success');
        btn.style.cssText = 'width:55px;height:55px;font-weight:700;';
        btn.textContent = i;
        btn.disabled = isOccupied;
        btn.title = isOccupied ? 'Bureau déjà réservé' : 'Bureau n°' + i + ' (libre)';
       
        if (!isOccupied) {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#seat-grid .btn').forEach(b => {
                    if (!b.disabled) {
                        b.classList.remove('btn-success');
                        b.classList.add('btn-outline-success');
                    }
                });
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
                document.getElementById('numero_siege').value = i;
                document.getElementById('selected-seat-label').textContent = '✅ Bureau n°' + i + ' sélectionné';
            });
        }
       
        grid.appendChild(btn);
    }
}

dateInput.addEventListener('change', loadSeats);
hdInput.addEventListener('change', loadSeats);
hfInput.addEventListener('change', loadSeats);
window.addEventListener('load', loadSeats);
</script>
@endif
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-check me-2"></i>Réserver maintenant
                            </button>
                            <a href="{{ route('espaces.show', $espace->IdEspace) }}" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection