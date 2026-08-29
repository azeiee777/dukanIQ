<?php

namespace App\Http\Controllers;

use App\Services\ProductService;

class StockController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('stock.index', [
            'products' => $this->service->listAll(),
            'stockValue' => $this->service->totalStockValue(),
            'lowStock' => $this->service->lowStockProducts(),
        ]);
    }
}
