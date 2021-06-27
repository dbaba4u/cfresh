<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Shift;
use App\Team;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teams = Team::all();
        return view('admin.shifts.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShiftRequest $request)
    {
       Shift::create($request->all());
        $notification = array(
            'message' => 'Shift created successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('shift.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        $teams = Team::all();
        return view('admin.shifts.edit', compact('teams','shift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        Shift::find($shift->id)->update($request->all());

        $notification = array(
            'message' => 'Shift record updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('shift.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        $notification = array(
            'message' => 'Shift record deleted successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('shift.index')->with($notification);
    }
}
