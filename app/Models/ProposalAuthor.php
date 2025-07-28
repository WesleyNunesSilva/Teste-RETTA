<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany
};

class ProposalAuthor extends Model
{
    protected $fillable = [
        'legislative_proposal_id',
        'deputy_id',
        'name',
        'type',
        'uri',
        'is_proponent'
    ];
    
    public function legislativeProposal()
    {
        return $this->belongsTo(LegislativeProposal::class);
    }
    
    public function deputy()
    {
        return $this->belongsTo(Deputy::class);
    }
}