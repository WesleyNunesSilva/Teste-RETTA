<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoliticalParty extends Model
{
    protected $fillable = [
        'external_id', 
        'acronym', 
        'name'
    ];
    
    public function deputies(): HasMany
    {
        return $this->hasMany(Deputy::class);
    }
}