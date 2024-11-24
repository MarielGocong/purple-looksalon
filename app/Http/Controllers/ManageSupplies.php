<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;

class ManageSupplies extends Controller
{
    public function index()
    {
        $supply = Supply::paginate(10);

        return view('dashboard.manage-supplies.index',
                [
                    'supply' => $supply,
                ]
        );
    }

    public function create()
    {
        return view('dashboard.manage-supplies.create');
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
    public function destroy(Supply $supply)
    {
        //
    }
}


