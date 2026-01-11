<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:sale,expense',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'nullable|string|max:50' // Matches migration
        ]);

        $this->service->createTransaction($validated);

        return redirect()->route('dashboard')->with('success', 'Entry added successfully');
    }

    public function destroy($id)
    {
        $this->service->deleteTransaction($id);
        return redirect()->route('dashboard')->with('success', 'Entry deleted successfully');
    }

    public function export(Request $request)
    {
        // Reuse the exact same filter logic as the dashboard
        $transactions = $this->service->getFilteredTransactions(
            Auth::id(),
            $request->all()
        );

        $filename = "dukaniq-export-" . date('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, ['Date', 'Type', 'Category', 'Description', 'Amount']);

            // CSV Rows
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->date->format('Y-m-d'),
                    ucfirst($t->type),
                    $t->category ?? '-', // Handle null category
                    $t->description,
                    $t->amount
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}