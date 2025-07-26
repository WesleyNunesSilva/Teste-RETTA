<?php

namespace App\Http\Controllers;

use App\Services\PoliticalPartyService;
use Illuminate\Http\Request;
use App\Models\Deputy;

class PoliticalPartyController
{
    public function __construct(
        protected PoliticalPartyService $partyService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['name', 'acronym']);
        $parties = $this->partyService->getFiltered($filters);
    
        return view('political-parties.index', compact('parties'));
    }
    

    public function show(int $id)
    {
        $party = $this->partyService->find($id);

        if (!$party) {
            return redirect()->route('political-parties.index')->with('error', 'Partido não encontrado.');
        }

        return view('political-parties.show', compact('party'));
    }
}
