<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class ManageEquipments extends Controller
{
    public function index()
    {
        $equipment = Equipment::paginate(10);

        return view('dashboard.manage-equipments.index',
                [
                    'equipment' => $equipment,
                ]
        );
    }

    public function create()
    {
        return view('dashboard.manage-equipments.create');
    }

    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        //
    }
}


