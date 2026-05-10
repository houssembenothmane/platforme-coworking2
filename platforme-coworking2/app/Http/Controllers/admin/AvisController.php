<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;

class AvisController extends Controller
{
    public function index()
    {
        $avis = Avis::with(['client', 'espace'])
                    ->latest()
                    ->paginate(20);
        return view('admin.avis.index', compact('avis'));
    }

    public function destroy($id)
    {
        Avis::findOrFail($id)->delete();
        return back()->with('success', 'Avis supprimé.');
    }
}
