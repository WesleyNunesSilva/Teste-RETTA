<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseService
{
    public function getFilteredExpensesByDeputy(int $deputyId, array $filters, int $perPage = 15)
    {
        $query = Expense::where('deputy_id', $deputyId);
    
        if (!empty($filters['supplier_name'])) {
            $query->where('supplier_name', 'like', '%' . $filters['supplier_name'] . '%');
        }
    
        if (!empty($filters['expense_type'])) {
            $query->where('expense_type', 'like', '%' . $filters['expense_type'] . '%');
        }
    
        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
    
        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }
    
        $allowedSorts = ['year', 'month', 'supplier_name', 'expense_type', 'amount'];
        $sortBy = $filters['sort_by'] ?? null;
        $sortDirection = strtolower($filters['sort_direction'] ?? 'asc');
    
        if (in_array($sortBy, $allowedSorts)) {
            $direction = $sortDirection === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortBy, $direction);
        } else {
            // ordenação padrão
            $query->orderBy('year', 'desc')->orderBy('month', 'desc');
        }
    
        return $query->paginate($perPage);
    }
        

    public function find(int $id): ?Expense
    {
        
    }

}
