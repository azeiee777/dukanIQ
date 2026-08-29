<?php

namespace App\Http\Controllers;

use App\Services\UdhariService;

class UdhariController extends Controller
{
    protected $service;

    public function __construct(UdhariService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('udhari.index', [
            'parties' => $this->service->listParties(),
            'totals' => $this->service->totals(),
        ]);
    }
}
