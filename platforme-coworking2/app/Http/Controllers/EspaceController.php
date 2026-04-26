<?php

namespace App\Http\Controllers;

use App\Models\Espace;
use App\Models\Reservation;
use Illuminate\Http\Request;

class EspaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Espace::withCount('avis')->withAvg('avis', 'note');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix_heure', '<=', $request->prix_max);
        }
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $espaces = $query->paginate(12);
        return view('espaces.index', compact('espaces'));
    }

    public function show($id)
    {
        $espace = Espace::with(['avis.client'])->findOrFail($id);
        $noteMoyenne = round($espace->avis->avg('note'), 1);
        return view('espaces.show', compact('espace', 'noteMoyenne'));
    }
    public function plan()
{
    $now = now();
    $today = $now->toDateString();
    $espaces = \App\Models\Espace::all();

    $occupiedIds = \App\Models\Reservation::where('date', $today)
        ->where('heure_debut', '<=', $now->format('H:i:s'))
        ->where('heure_fin',   '>=', $now->format('H:i:s'))
        ->where('statut', '!=', 'Annulée')
        ->pluck('espace_id')->toArray();

    $soonIds = \App\Models\Reservation::where('date', $today)
        ->where('heure_debut', '>',  $now->format('H:i:s'))
        ->where('heure_debut', '<=', $now->copy()->addHour()->format('H:i:s'))
        ->where('statut', '!=', 'Annulée')
        ->pluck('espace_id')->toArray();

    $myReservation = null;
    if (auth('client')->check()) {
        $myReservation = \App\Models\Reservation::with('espace')
            ->where('client_id', auth('client')->id())
            ->where('date', $today)
            ->where('heure_fin', '>=', $now->format('H:i:s'))
            ->where('statut', '!=', 'Annulée')
            ->orderBy('heure_debut')
            ->first();
    }

    return view('plan', compact('espaces', 'occupiedIds', 'soonIds', 'myReservation'));
}
    public function adminIndex()
    {
        $espaces = Espace::withCount(['reservations', 'avis'])->get();
        $totalReservations = Reservation::count();
        return view('admin.espaces.index', compact('espaces', 'totalReservations'));
    }

    public function create()
    {
        return view('admin.espaces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|min:3|max:120',
            'description' => 'nullable|max:500',
            'prix_heure'  => 'required|numeric|min:1|max:9999',
            'capacite'    => 'required|integer|min:1|max:500',
            'statut'      => 'required|in:Disponible,Indisponible',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);

        Espace::create($request->only([
            'nom', 'description', 'prix_heure', 'capacite',
            'statut', 'latitude', 'longitude'
        ]));

        return redirect()->route('admin.espaces.index')
            ->with('success', 'Espace créé avec succès !');
    }

    public function edit($id)
    {
        $espace = Espace::findOrFail($id);
        return view('admin.espaces.edit', compact('espace'));
    }

    public function update(Request $request, $id)
    {
        $espace = Espace::findOrFail($id);

        $request->validate([
            'nom'         => 'required|min:3|max:120',
            'description' => 'nullable|max:500',
            'prix_heure'  => 'required|numeric|min:1',
            'capacite'    => 'required|integer|min:1',
            'statut'      => 'required|in:Disponible,Indisponible',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
        ]);

        $espace->update($request->only([
            'nom', 'description', 'prix_heure', 'capacite',
            'statut', 'latitude', 'longitude'
        ]));

        return redirect()->route('admin.espaces.index')
            ->with('success', 'Espace modifié avec succès !');
    }

    public function destroy($id)
    {
        $espace = Espace::findOrFail($id);
        $espace->delete();
        return redirect()->route('admin.espaces.index')
            ->with('success', 'Espace supprimé.');
    }
}