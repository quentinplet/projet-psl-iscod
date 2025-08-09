<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementHistoryRequest;
use App\Http\Requests\UpdateStockMovementHistoryRequest;
use App\Models\StockMovementHistory;

class StockMovementHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockMovementHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovementHistory $stockMovementHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockMovementHistoryRequest $request, StockMovementHistory $stockMovementHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMovementHistory $stockMovementHistory)
    {
        //
    }
}
