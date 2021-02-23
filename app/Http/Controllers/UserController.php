<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(Request $req)
    {
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'country' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|max:16',
            'c_password' => 'required|min:8|max:16|same:password'
        );

        $user = User::where('email', $req->email)->first();

        if ($user) {
            return response()->json([
                'error' => true,
                'response' => 'Este email ya está registrado, intenta con otro'
            ], 203);
        } else {
            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 202);
            }

            $input = $req->all();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['user'] = $user;

            return response()->json(['error' => false, 'response' => $responseArray], 200);
        }
    }

    public function login(Request $req)
    {
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $user = Auth::user();
            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['user'] = $user;

            return response()->json($responseArray, 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 203);
        }
    }

    public function logout(Request $req)
    {
        $req->user()->tokens()->delete();
        return response()->json([
            'error' => false,
            'delete' => true
        ], 200);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users, 200,);
    }

    public function store(Request $req)
    {
        $rules = array(
            "name" => 'required',
            "email" => 'required|email',
            'password' => 'required|min:8|max:16'
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        } else {
            $user = User::create($req->all());
            return response()->json([
                'error' => false,
                'response' => 'Usuario creado con éxito',
                'user' => $user
            ], 200);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, User $user)
    {
        $rules = array(
            "name" => 'required',
            "email" => 'required|email',
            'password' => 'required|min:8|max:16'
        );
        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        } else {
            $edited_user = $user->update($req->all());
            return response()->json([
                'error' => false,
                'response' => 'Usuario actualizado con éxito',
                'user' => $user
            ], 200);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'error' => false,
            'response' => 'Usuario eliminado con éxito'
        ], 200);
    }

    public function getUserByToken()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $data = [
                'error' => false,
                'user' => $user
            ];
            return response()->json($data, 200);
        }
        return response()->json([
            'error' => true,
            'response' => 'Invalid token'
        ], 404);
    }
}
