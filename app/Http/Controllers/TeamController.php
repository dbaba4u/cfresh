<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\TeamRequest;
use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
//        $teams = Team::all();
        return view('admin.teams.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        Team::create($request->only('name', 'unit','employee_id'));

        $notification = array(
            'message' => 'Team created successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('teams.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $employees = Employee::all();
        return view('admin.teams.edit', compact('team','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
//        dd($team);
        Team::find($team->id)->update($request->only('name', 'unit','employee_id'));

        $notification = array(
            'message' => 'Team record updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('teams.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        $notification = array(
            'message' => 'Team record deleted successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('teams.index')->with($notification);
    }
}
