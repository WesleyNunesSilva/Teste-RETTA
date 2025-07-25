<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany
};

class Deputy extends Model
{
    protected $fillable = ['external_id', 'political_party_id', 'name', 'state_acronym', 'email'];
    
    public function PoliticalParty(): BelongsTo
    {
        return $this->belongsTo(PoliticalParty::class);
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function legislativeProposals(): HasMany
    {
        return $this->hasMany(LegislativeProposal::class);
    }
    
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}