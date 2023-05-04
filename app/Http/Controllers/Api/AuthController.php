<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator= Validator($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:4',
            'password_c'=>'required|same:password',
        ]);
        if ($validator->fails()){
            return [
                'code' => 401,
                'message' => $validator->getMessageBag()->first(),
            ];
        }
        $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token=$user->createToken('paystore')->accessToken;

        return [
            'code' => 200,
            'user' => $user,
            'token' => $token
        ];
    }


    public function login (Request $request) {
        $validator= Validator($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($validator->fails()){
            return [
                'code' => 401,
                'message' => $validator->getMessageBag()->first(),
            ];
        }

        $user= User::where('email', $request->email)->first();
        if(! $user){
            return [
                'code' => 401,
                'message' => 'کاربری با این ایمیل وجود ندارد',
            ];

        }

        if(Hash::check($user->password , $request->password)){
            return [
                'code' => 401,
                'message' => 'پسورد اشتباه است.' ,
            ];
        }

        $token = $user->createToken('paystore')->accessToken;

        return [
            'code' => 200,
            'user' => $user,
            'token' => $token
        ];

    }

    public function logout(Request $request){

        $request->user()->token()->delete();
        // auth()->user()->token()->delete();

        return 'ok';

    }

    public function check(){
        Auth::guard('api')->check();
        return 'user is loggedin';
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json(['user' => $user], 200);
    }
}
