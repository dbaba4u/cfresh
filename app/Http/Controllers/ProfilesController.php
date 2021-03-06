<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Session::get('adminDetails');
//        dd($admin->employee);

        return view('admin.users.profile',compact('admin'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'address'=>'required',
            'phone'=>'required'

        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar'))
        {
            $image = $request->avatar;
            $new_image_name = time().$image->getClientOriginalName();
            $image->move('uploads/avatars', $new_image_name);
            $user->profile->avatar = 'uploads/avatars/'.$new_image_name;

            $user->profile->save();
        }

        $user->name=$request->name;
        $user->email=$request->email;
        $user->profile->address=$request->address;
        $user->profile->phone=$request->phone;

        $user->profile->save();

        if (!empty($request->password))
        {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $notification = array(
            'message' => 'Account profile updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
