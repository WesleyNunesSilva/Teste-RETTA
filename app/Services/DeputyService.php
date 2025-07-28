<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Deputy;
use Illuminate\Http\Request;

class DeputyService
{
    public function getFilteredDeputies(Request $request, int $perPage = 15) 
    {
        $query = Deputy::with('politicalParty');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('political_party_id')) {
            $query->where('political_party_id', $request->political_party_id);
        }

        if ($request->filled('state_acronym')) {
            $query->where('state_acronym', $request->state_acronym);
        }

        return $query->paginate($perPage);
    } 

    public function find(int $id): ?Deputy
    {
        return Deputy::with([
            'politicalParty',
            'expenses',
            'legislativeProposals' => function ($query) {
                $query->latest();
            }
        ])->find($id);
    }
}
