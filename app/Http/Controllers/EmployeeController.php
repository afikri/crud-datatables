<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::select('id', 'name', 'address', 'phone_nr', 'email')->get();
            return Datatables::of($employees)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
                    $btn = '<a href="javascript:void(0);" data-id="' . $row->id . '"  class="btn btn-warning btn-sm editEmployee">Edit</a> ';
                    $btn .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteEmployee">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Employee::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'address' => $request->address,
                'phone_nr' => $request->phone_nr,
                'email'=>$request->email
            ]
        );

        return response()->json(['success' => 'Employee saved successfully.']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();

        return response()->json(['success' => 'Employee deleted successfully.']);
    }
}
