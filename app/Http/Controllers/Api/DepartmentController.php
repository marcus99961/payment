<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        return $this->middleware('auth:api');
    }
    public function index()
    {
        $departments = Department::get();
        return response()->json($departments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $department = new Department;
        $department->name = $request->name;




        $department->save();
        return response()->json('Success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       Department::find($id)->update([
            'name'=>$request->name,


        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Department::find($id)->delete();
    }
}
