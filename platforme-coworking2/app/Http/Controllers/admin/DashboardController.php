<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Espace;
use App\Models\Reservation;
use App\Models\Avis;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalClients'      => Client::count(),
            'totalReservations' => Reservation::count(),
            'totalEspaces'      => Espace::count(),
            'totalAvis'         => Avis::count(),
        ]);
    }
}