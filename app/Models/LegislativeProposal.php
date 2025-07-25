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
        'type_acronym', // sigla_tipo
        'number',
        'year',
        'summary', // ementa
        'status' // situacao
    ];
    
    public function deputy(): BelongsTo
    {
        return $this->belongsTo(Deputy::class);
    }
    
    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class);
    }
}