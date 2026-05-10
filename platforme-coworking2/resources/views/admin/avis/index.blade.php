@extends('layouts.app')
@section('title', 'Gestion des Avis')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-star me-2 text-warning"></i>Gestion des Avis</h2>

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
                        <th>Client</th>
                        <th>Espace</th>
                        <th>Note</th>
                        <th>Sentiment</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avis as $avi)
                    @php
                        $sentimentColor = ['positif'=>'success','neutre'=>'secondary','negatif'=>'danger'];
                        $sentimentIcon  = ['positif'=>'😊','neutre'=>'😐','negatif'=>'😞'];
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $avi->client->nom ?? '—' }}</strong>
                            <div class="text-muted small">{{ $avi->client->email ?? '' }}</div>
                        </td>
                        <td>{{ $avi->espace->nom ?? '—' }}</td>
                        <td>
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $avi->note ? 'text-warning' : 'text-muted' }}"
                                   style="font-size:.8rem"></i>
                            @endfor
                            <span class="text-muted small ms-1">{{ $avi->note }}/5</span>
                        </td>
                        <td>
                            @if($avi->sentiment)
                                <span class="badge bg-{{ $sentimentColor[$avi->sentiment] ?? 'secondary' }}">
                                    {{ $sentimentIcon[$avi->sentiment] ?? '' }} {{ ucfirst($avi->sentiment) }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td style="max-width:250px">
                            <span class="text-muted small">
                                {{ $avi->commentaire ? Str::limit($avi->commentaire, 80) : '—' }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $avi->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.avis.destroy', $avi->IdAvis) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer cet avis ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucun avis enregistré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($avis->hasPages())
        <div class="card-footer">{{ $avis->links() }}</div>
        @endif
    </div>
</div>
@endsection
