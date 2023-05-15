<?php

namespace App\Http\Controllers;

use App\Models\Planner;
use App\Http\Requests\StorePlannerRequest;
use App\Http\Requests\UpdatePlannerRequest;

class PlannerController extends Controller
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
    public function store(StorePlannerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Planner $planner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlannerRequest $request, Planner $planner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planner $planner)
    {
        //
    }
}
