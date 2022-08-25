<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tokens;
use Datetime;


class AuthController extends Controller
{
    function login(Request $req){
        $validator = Validator::make($req->all(),[
            "email"=>"required|email",
            "password"=>"required|min:4",
        ],
        [
            "email.required"=>"Provide your Email",
            "password.required"=>"Provide your ",

        ]);


        $userCount = DB::table('users')
            ->where('email', $req->email)
            ->count();
        if($userCount>0){
            $user = DB::table('users')
                ->where('email', $req->email)
                ->first();
            if (password_verify($req->password, $user->password)) {
                $key = Str::random(67);
                $token = new Tokens();
                $token->tkey = $key;
                $token->user_id = $user->id;
                $token->created_at = new Datetime();
                $token->role = $user->usertype;
                $token->save();
                return response()->json($token);
            }
            return response()->json(["msg"=>"Username password invalid"],404);
        }
        return response()->json(["msg"=>"Username password invalid"],404);
        
    }

    function logout(Request $req){
        $tk = $req->token;
        $token = Tokens::where('tkey',$tk)->first();
        $token->expired_at = new Datetime();
        $token->update();
        return response()->json(["msg"=>"Logged out"]);
    }

    function register(Request $req){
        $validator = Validator::make($req->all(),[
            "name"=>"required|max:30|min:3|regex:/^[a-z ,.'-]+$/i",
            "email"=>"required|email",//|unique:users,email",
            "type"=>"required",
            "password"=>"required|min:4",//|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}+$/i",
            "conf_password"=>"required|min:4|same:password",
        ],
        [
            "name.required"=>"Provide your name",
            "name.regex"=>"Provide valid name",
            "password.regex"=>"Password must contain upper case,lower case,number and special characters",
            "conf_password.required"=>"Confirm your password",
            "conf_password.same"=>"Confirm password and password don't match"
        ]
         );
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }


        $emailCount = DB::table('users')
            ->where('email', $req->email)
            ->count();

        if($emailCount>0){
            return response()->json(
                [
                    "msg"=>"Email already exists",    
                ]
            );
        }


        if($req->type == 'Doctor'){
            DB::table('users')->insert(
                array(
                    'name' => $req->name,
                    'email' => $req->email,
                    'password' => Hash::make($req->password),
                    'usertype' => $req->type,
                )
            ); 
        }


        return response()->json(
            [
                "msg"=>"Registered Successfully",   
            ]
        );
    }

}
