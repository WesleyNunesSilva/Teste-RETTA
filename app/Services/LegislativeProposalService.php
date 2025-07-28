<?php

namespace App\Services;

use App\Models\LegislativeProposal;
use Illuminate\Http\Request;

class LegislativeProposalService
{
    public function getFiltered(Request $request, int $perPage = 15)
    {
        $query = LegislativeProposal::with(['deputy', 'authors']);

        if ($request->filled('type_acronym')) {
            $query->where('type_acronym', 'like', '%' . $request->type_acronym . '%');
        }

        if ($request->filled('number')) {
            $query->where('number', $request->number);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('summary')) {
            $query->where('summary', 'like', '%' . $request->summary . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?LegislativeProposal
    {
        return LegislativeProposal::with(['deputy', 'authors'])->find($id);
    }
}
