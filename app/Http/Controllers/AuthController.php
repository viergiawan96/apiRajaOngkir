<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);
 
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
 
        $hashPwd = Hash::make($password);
 
        $data = [
            "name" => $name,
            "email" => $email,
            "password" => $hashPwd
        ];
 
 
 
        if (User::create($data)) {
            $results = [
                'meta' => [
                    "code"   => 201,
                    "message" => "register_success",
                ]
            ];
        } else {
            $results = [
                'meta' => [
                    "code"   => 404,
                    "message" => "vailed_register",
                ]
            ];
        }
 
        return response()->json($results, $results['meta']['code']);

    }

    
    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->input("email");
        $password = $request->input("password");

       $user = user::where('email', $email)->first();
    
       if(!$user) {
           $out = [
               'message' => 'login_vailed',
               'code' => 401,
               'results' => [
                   'token' => null
               ]
               ];
               return response()->json($out,$out['code']);
       }

       if(Hash::check($password, $user->password)) {
            $newtoken  = $this->generateRandomString();

           $user->update([
               'api_key' => $newtoken
           ]);
           
            $results = [
                'meta' => [
                    "message" => "login_success",
                    "code"    => 200,
                    "result"  => [
                    "token" => $newtoken,
                     ]
                ]
            ];
       } else {
            $results = [
                'meta' => [
                    "message" => "login_vailed",
                    "code"    => 401,
                    "result"  => [
                        "token" => null,
                    ]
                ]
            ];
       }

       return response()->json($results, $results['meta']['code']);

    }

    function generateRandomString($length = 80)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }
}