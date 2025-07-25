<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'voting_id',
        'deputy_id',
        'vote_type',
    ];
    
    public function voting(): BelongsTo
    {
        return $this->belongsTo(Voting::class);
    }
    
    public function deputy(): BelongsTo
    {
        return $this->belongsTo(Deputy::class);
    }
}