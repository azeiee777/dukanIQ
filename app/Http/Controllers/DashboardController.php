<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get transactions based on current filters (Date, Type, Category)
        $transactions = $this->service->getFilteredTransactions($userId, $request->all());

        // Calculate stats based on the FILTERED view. 
        // Example: If viewing "This Month", stats will show profit for this month only.
        $income = $transactions->where('type', 'sale')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        return view('dashboard', [
            'transactions' => $transactions,
            'stats' => [
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense
            ],
            // Pass filters back to view to keep UI state active
            'currentFilters' => [
                'date' => $request->get('date', 'month'),
                'type' => $request->get('type', 'all'),
                'category' => $request->get('category', 'all')
            ]
        ]);
    }
}