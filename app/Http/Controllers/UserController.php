<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function register(Request $req){
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'country' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|max:16',
            'c_password' => 'required|min:8|max:16|same:password'
        );
        
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 202);
        }

        $input = $req->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $responseArray = [];
        $responseArray['token'] = $user->createToken('MyApp')->accessToken;
        $responseArray['user'] = $user;

        return response()->json($responseArray, 200);
    }

    public function login(Request $req){
        if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){
            $user = Auth::user();
            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['user'] = $user;

            return response()->json($responseArray, 200);

        } else {
            return response()->json(['error'=>'Unauthenticated'], 203);
        }
    }

    public function index(){
        $users = User::all();
        return $users;
    }

    public function store(Request $req)
    {
        $rules = array(
            "name"=>'required',
            "email"=>'required|email',
            'password'=>'required|min:8|max:16'
        );
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 401);            
        } else {
            $user = User::create($req->all());
            return response()->json([
                'response' => 'Usuario creado con éxito',
                'user' => $user
        ], 200);
        }
    }

    public function show($id){
        $user = User::find($id);
        return $user;
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
            "name"=>'required',
            "email"=>'required|email',
            'password'=>'required|min:8|max:16'
        );
        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 401);            
        } else {
            $edited_user = $user->update($req->all());
            return response()->json([
                'response' => 'Usuario actualizado con éxito',
                'user' => $user
        ], 200);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'response'=>'Usuario eliminado con éxito'
        ], 200);
    }
}