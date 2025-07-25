<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deputy;
use App\Models\PoliticalParty;
use App\Services\DeputyService;
use App\Services\ExpenseService;

class DeputyController
{
    protected DeputyService $deputyService;

    public function __construct(DeputyService $deputyService)
    {
        $this->deputyService = $deputyService;
    }

    public function index(Request $request, int $perPage = 15)
    {
        $deputies = $this->deputyService->getFilteredDeputies($request);
        $parties = PoliticalParty::all();

        return view('deputies.index', compact('deputies', 'parties'));
    }

    public function show(int $id, Request $request, ExpenseService $expenseService)
    {
        $deputy = $this->deputyService->find($id);

        if(!$deputy) {
            return redirect()->route('deputies.index')->with(
                'error', 
                'Deputado não encontrado.'
            );
        }

        $filters = $request->only([
            'supplier_name',
            'expense_type',
            'year',
            'month',
            'sort_by',
            'sort_direction',
        ]);

        $expenses = $expenseService->getFilteredExpensesByDeputy($deputy->id, $filters);

        $politicalParty = $deputy->politicalParty;

        return view('deputies.show', compact('deputy', 'expenses', 'politicalParty', 'filters'));
    }
}
