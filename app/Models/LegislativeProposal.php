<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany
};

class LegislativeProposal extends Model
{
    protected $fillable = [
        'external_id', 
        'deputy_id',
        'type_acronym',
        'number',
        'year',
        'summary',
    ];
    
    public function deputy(): BelongsTo
    {
        return $this->belongsTo(Deputy::class);
    }
    
    public function authors()
    {
        return $this->hasMany(ProposalAuthor::class);
    }

}