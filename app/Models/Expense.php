<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'external_id',
        'deputy_id',
        'year', 
        'month', 
        'expense_type', 
        'amount', 
        'supplier_name'
    ];
    
    public function deputy(): BelongsTo
    {
        return $this->belongsTo(Deputy::class);
    }
}