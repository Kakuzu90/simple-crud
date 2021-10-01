<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->toArray();
        return array_reverse($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first' => 'required',
            'last' => 'required',
            'username' => 'required|unique:user',
            'password' => 'required|confirmed',
            'role' => 'required'
        ]);

        $create = User::create([
            'first_name' => ucwords($request->first),
            'last_name' => ucwords($request->last),
            'username' => $request->username,
            'password' => Hash::make($request->passwprd),
            'role' => $request->role
        ]);

        return response()
                ->json(['message' => 'The user was added successfully.', 'data' => $create], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('User not found!', 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('User not found!', 404);
        }
        $user->first_name = ucwords($request->first);
        $user->last_name = ucwords($request->last);
        $user->username = $request->username;
        if ($request->filled('password')) {

            if ($request->password != $request->confirm) {
                return response()->json('Password does not match.', 200);
            }

            $user->password = Hash::make($request->password);

        }
        $user->role = $request->role;
        $user->save();

        return response()
                ->json(['message' => 'The user was updated successfully.', 'data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('User not found!', 404);
        }
        $user->delete();

        return response()->json('The user deleted successfully', 200);
    }
}
