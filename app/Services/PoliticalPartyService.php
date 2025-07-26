<?php

namespace App\Services;

use App\Models\PoliticalParty;

class PoliticalPartyService
{

    public function getFiltered(array $filters)
    {
        $query = PoliticalParty::query();
    
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
    
        if (!empty($filters['acronym'])) {
            $query->where('acronym', 'like', '%' . $filters['acronym'] . '%');
        }
    
        return $query->paginate(15);
    }

    public function find(int $id): ?PoliticalParty
    {
        return PoliticalParty::with('deputies')->find($id);
    }
}

