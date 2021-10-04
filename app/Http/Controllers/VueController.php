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
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);
        $checkUsername = User::where('username', $request->username)->count();
        if($checkUsername > 0) {
            return response()->json([
                'message' => 'The username is already taken.',
                'error' => true], 200);
        }
        if ($request->password != $request->confirm) {
            return response()->json([
                'message' => 'The password doest not match.',
                'error' => true], 200);
        }
        $create = User::create([
            'first_name' => ucwords($request->first_name),
            'last_name' => ucwords($request->last_name),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()
                ->json([
                    'message' => 'Added!',
                    'error' => false,
                    'data' => $create], 200);
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
        $user->first_name = ucwords($request->first_name);
        $user->last_name = ucwords($request->last_name);
        $user->username = $request->username;
        if ($request->filled('password')) {

            if ($request->password != $request->confirm) {
                return response()->json([
                    'message' => 'The password does not match.',
                    'error' => true
                ], 200);
            }

            $user->password = Hash::make($request->password);

        }
        $user->role = $request->role;
        $user->save();

        return response()
                ->json(['message' => 'Updated!',
                'error' => false, 'data' => $user], 200);
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

    public function search($search, $orderBy, $sort) {

        if ($search == "*") {
            $users = User::orderBy($orderBy, $sort == 1 ? 'asc' : 'desc')->get()->toArray();
        }else {
            $users = User::where('first_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhere('role', 'LIKE', "%$search%")
                        ->orderBy($orderBy, $sort == 1 ? 'asc' : 'desc')->get()->toArray();
        }

        return $users;
    }

}
