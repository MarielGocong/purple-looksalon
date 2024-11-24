<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class ManageEmployee extends Controller
{
    public function index()
    {
        $employee = Employee::paginate(10);

        return view('dashboard.manage-employees.index',
                [
                    'employee' => $employee,
                ]
        );
    }

    public function create()
    {
        return view('dashboard.manage-employees.create');
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
    public function destroy(Employee $employee)
    {
        //
    }
}
