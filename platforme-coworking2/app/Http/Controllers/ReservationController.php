<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Espace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('espace')
            ->where('IdClient', auth('client')->user()->IdClient)
            ->orderByDesc('created_at')
            ->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create($espace_id)
    {
        $espace = Espace::findOrFail($espace_id);

        $hasSeats = in_array($espace->nom, [
            'Open Space – Zone Silence',
            'Open Space – Zone Collaborative',
            "Salle Conférence – L'Auditorium"
        ]);

        return view('reservations.create', compact('espace', 'hasSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'espace_id'    => 'required|exists:espaces,IdEspace',
            'date'         => 'required|date|after_or_equal:today',
            'heure_debut'  => 'required',
            'heure_fin'    => 'required|after:heure_debut',
            'numero_siege' => 'nullable|integer|min:1',
        ]);

        // Vérifier si le siège est déjà pris
        if ($request->numero_siege) {
            $conflit = Reservation::where('IdEspace', $request->espace_id)
                ->where('numero_siege', $request->numero_siege)
                ->where('date', $request->date)
                ->where('statut', '!=', 'Annulée')
                ->where(function ($q) use ($request) {
                    $q->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                      ->orWhereBetween('heure_fin',  [$request->heure_debut, $request->heure_fin])
                      ->orWhere(function ($q2) use ($request) {
                          $q2->where('heure_debut', '<=', $request->heure_debut)
                             ->where('heure_fin',   '>=', $request->heure_fin);
                      });
                })->exists();

            if ($conflit) {
                return back()->with('error', 'Ce bureau est déjà réservé à cette heure. Choisissez-en un autre.')->withInput();
            }
        }

        Reservation::create([
            'IdClient'     => auth('client')->user()->IdClient,
            'IdEspace'     => $request->espace_id,
            'numero_siege' => $request->numero_siege,
            'date'         => $request->date,
            'heure_debut'  => $request->heure_debut,
            'heure_fin'    => $request->heure_fin,
            'statut'       => 'Confirmée',
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation confirmée !' . ($request->numero_siege ? ' Bureau n°' . $request->numero_siege : ''));
    }

    public function show($id)
    {
        $reservation = Reservation::with('espace')
            ->where('IdClient', auth('client')->user()->IdClient)
            ->findOrFail($id);
        $facture = $reservation->genererFacture();
        return view('reservations.show', compact('reservation', 'facture'));
    }

    public function destroy($id)
    {
        $reservation = Reservation::where('IdClient', auth('client')->user()->IdClient)
            ->findOrFail($id);
        $reservation->update(['statut' => 'Annulée']);
        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée.');
    }

    public function adminIndex()
    {
        $reservations = Reservation::with(['client', 'espace'])
            ->orderByDesc('created_at')
            ->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatut(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $request->validate([
            'statut' => 'required|in:Confirmée,En attente,Annulée,Terminée',
        ]);
        $reservation->update(['statut' => $request->statut]);
        return back()->with('success', 'Statut mis à jour.');
    }
}