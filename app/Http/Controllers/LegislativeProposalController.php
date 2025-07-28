<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LegislativeProposalService;
use App\Services\DeputyService;

class LegislativeProposalController
{
    public function __construct(protected LegislativeProposalService $service, protected DeputyService $deputyService) {}

    public function index(Request $request)
    {
        $proposals = $this->service->getFiltered($request);
        return view('legislative-proposals.index', compact('proposals'));
    }

    public function show(int $id)
    {
        $proposal = $this->service->find($id);
        abort_unless($proposal, 404);
    
        // Pegar o primeiro autor que é deputado (se existir)
        $deputy = null;
        if ($proposal->authors->isNotEmpty()) {
            $deputy = $proposal->authors->firstWhere('type', 'Deputado(a)');
        }
    
        return view('legislative-proposals.show', compact('proposal', 'deputy'));
    }
}
