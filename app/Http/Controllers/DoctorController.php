<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    function register(Request $req){
        $validator = Validator::make($req->all(),[
            "name"=>"required|max:30|min:3|regex:/^[a-z ,.'-]+$/i",
            "email"=>"required|email",//|unique:user,email",
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
        $doctor=new Doctor();
        $doctor ->name = $req->name;
        $doctor ->email =$req->email;
        $doctor ->password =$req->password;
        $doctor-> type = $req->type;
        $doctor->save();
        return response()->json(
            [
                "msg"=>"Registered Successfully",
                "data"=>$doctor      
            ]
        );
    }
    function getDoctor(){
        $doctor=Doctor::whereNull('status')->get();
        return response()->json($doctor);
    }
    function updateDoctor(Request $req,$id){
        $validator = Validator::make($req->all(),[
            "name"=>"required|max:30|min:3|regex:/^[a-z ,.'-]+$/i",
            "type"=>"required",
        ],
        [
            "name.required"=>"Provide your name",
            "name.regex"=>"Provide valid name",
        ]
         );
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $doctor=Doctor::where('id',$id)->first();
        $doctor ->name = $req->name;
        $doctor-> type = $req->type;
        $doctor->update();
        return response()->json(
            [
                "msg"=>"Update Successfully",
                "data"=>$doctor       
            ]
        );
    }
    function deleteDoctor($id){
        $doctor=Doctor::where('id',$id)->first();
        $doctor->status="Inactive";
        $doctor->update();
        return response()->json(
            [
                "msg"=>"Delete Successfull"
            ]
            );
    }
}
