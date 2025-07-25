<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voting extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'legislative_proposal_id',
        'title',
        'date',
        'result',
    ];

    public function legislativeProposal()
    {
        return $this->belongsTo(LegislativeProposal::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
