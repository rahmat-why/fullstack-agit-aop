<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function view()
    {
        return view('user');
    }

    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role', 'phone', 'address', 'created_at', 'updated_at')
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['data' => $users]);
    }

    public function getDeleted()
    {
        $users = User::select('id', 'name', 'email', 'role', 'phone', 'address', 'deleted_at')
            ->whereNotNull('deleted_at')
            ->get();

        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|string|max:50',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'password'   => Hash::make($request->password),
            'phone'      => $request->phone,
            'address'    => $request->address,
            'created_at' => now(),
        ]);

        return response()->json(['data' => $user], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,$id",
            'role'     => 'required|string|max:50',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6'
        ]);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->role     = $request->role;
        $user->phone    = $request->phone;
        $user->address  = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->updated_at = now();
        $user->save();

        return response()->json(['data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = now();
        $user->save();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
