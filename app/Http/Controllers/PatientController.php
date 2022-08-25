<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    function register(Request $req){
        $validator = Validator::make($req->all(),[
            "name"=>"required|max:30|min:3|regex:/^[a-z ,.'-]+$/i",
            "email"=>"required|email",//|unique:user,email",
            "age"=>"required",
            "phone"=>"required",
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
        $patient=new Patient();
        $patient ->name = $req->name;
        $patient ->email =$req->email;
        $patient ->age =$req->age;
        $patient ->phone =$req->phone;
        $patient ->password =$req->password;
        $patient-> type = $req->type;
        $patient->save();
        return response()->json(
            [
                "msg"=>"Registered Successfully",
                "data"=>$patient     
            ]
        );
    }
    function getSinglePatient($id){
        $patient=Patient::where('id',$id)->first();
        return response()->json($patient);
    }
    function getPatient(){
        $patient=Patient::whereNull('status')->get();
        return response()->json($patient);
    }
    function updatePatient(Request $req,$id){
        $validator = Validator::make($req->all(),[
            "prescription"=>"required"
        ],
         );
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $patient=Patient::where('id',$id)->first();
        $patient ->prescription = $req->prescription;
        $patient->update();
        return response()->json(
            [
                "msg"=>"Done",
                "data"=>$patient      
            ]
        );
    }
    function deletePatient($id){
        $patient=Patient::where('id',$id)->first();
        $patient->status="Inactive";
        $patient->update();
        return response()->json(
            [
                "msg"=>"Delete Successfull"
            ]
            );
    }
}
