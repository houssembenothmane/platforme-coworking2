<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Reservation;
use App\Models\Espace;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount(['reservations', 'avis'])->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function profil()
    {
        $client = auth('client')->user();
        $reservations = $client->reservations()
            ->with('espace')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        return view('clients.profil', compact('client', 'reservations'));
    }

    public function updateProfil(Request $request)
    {
        $client = auth('client')->user();
        $request->validate([
            'nom'   => 'required|min:2|max:80',
            'email' => 'required|email|unique:clients,email,' . $client->id,
        ], [
            'nom.required'   => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique'   => 'Cet email est déjà utilisé.',
        ]);

        $data = ['nom' => $request->nom, 'email' => $request->email];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = $request->password;
        }

        $client->update($data);
        return redirect()->route('profil')->with('success', 'Profil mis à jour !');
    }

    public function adminDashboard()
    {
        $totalClients      = Client::count();
        $totalReservations = Reservation::count();
        $totalEspaces      = Espace::count();
        return view('admin.dashboard', compact(
            'totalClients',
            'totalReservations',
            'totalEspaces'
        ));
    }                          // ← accolade fermante de adminDashboard

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client supprimé.');
    }
}                              // ← accolade fermante de la classe