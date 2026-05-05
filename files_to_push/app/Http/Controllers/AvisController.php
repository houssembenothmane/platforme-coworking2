<?php
namespace App\Http\Controllers;
use App\Models\Avis;
use App\Models\Espace;
use Illuminate\Http\Request;
use App\Services\AiService;
class AvisController extends Controller
{
    public function create($espace_id)
    {
        $espace = Espace::findOrFail($espace_id);
        return view('avis.create', compact('espace'));
    }
    public function store(Request $request, AiService $ai)
{
    $validated = $request->validate([
        'espace_id'   => 'required|exists:espaces,id',
        'note'        => 'required|integer|min:1|max:5',
        'commentaire' => 'nullable|string|max:1000',
    ]);
    $validated['client_id'] = auth('client')->id();
    // Analyse de sentiment si commentaire présent
    if (!empty($validated['commentaire'])) {
        $resultat = $ai->analyserSentiment($validated['commentaire']);
        $validated['sentiment']       = $resultat['sentiment'];
        $validated['sentiment_score'] = $resultat['score'];
    }
    Avis::create($validated);
    return redirect()->route('espaces.show', $validated['espace_id'])
        ->with('success', 'Merci pour votre avis !');
}
    public function destroy($id)
    {
        $avis = Avis::where('client_id', auth('client')->id())->findOrFail($id);
        $espaceId = $avis->espace_id;
        $avis->delete();
        return redirect()->route('espaces.show', $espaceId)->with('success', 'Avis supprimé.');
    }
}
