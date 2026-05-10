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
            'espace_id'   => 'required|exists:espaces,IdEspace',
            'note'        => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $data = [
            'IdClient' => auth('client')->user()->IdClient,
            'IdEspace' => $validated['espace_id'],
            'note'     => $validated['note'],
            'commentaire' => $validated['commentaire'] ?? null,
        ];

        if (!empty($validated['commentaire'])) {
            $resultat = $ai->analyserSentiment($validated['commentaire']);
            $data['sentiment']       = $resultat['sentiment'];
            $data['sentiment_score'] = $resultat['score'];
        }

        Avis::create($data);

        return redirect()->route('espaces.show', $validated['espace_id'])
            ->with('success', 'Merci pour votre avis !');
    }

    public function destroy($id)
    {
        $avis = Avis::where('IdClient', auth('client')->user()->IdClient)->findOrFail($id);
        $espaceId = $avis->IdEspace;
        $avis->delete();
        return redirect()->route('espaces.show', $espaceId)->with('success', 'Avis supprimé.');
    }
}
