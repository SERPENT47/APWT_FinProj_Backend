<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Pharmacy;
use Illuminate\Http\Request;


class PharmacyController extends Controller
{
    function getPharmacy(){
        $pharmacy=Pharmacy::whereNull('status')->get();
        return $pharmacy;
    }
    function addPharmacy(Request $req){
        $validator = Validator::make($req->all(),[
            "name"=>"required",
            "address"=>"required",
            "phone_no"=>"required|regex:/^(^\+?(88)?0?1[3456789][0-9]{8})+$/i"
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $pharmacy=new Pharmacy();
        $pharmacy->name=$req->name;
        $pharmacy->address=$req->address;
        $pharmacy->phone_no=$req->phone_no;
        $pharmacy->save();
      return response()->json([
        "msg"=>"Pharmacy Added successfully",
        "data"=>$pharmacy
      ]);
    } 
    function updatePharmacy(Request $req,$id){
        $validator = Validator::make($req->all(),[
            "name"=>"required",
            "address"=>"required",
            "phone_no"=>"required|regex:/^(^\+?(88)?0?1[3456789][0-9]{8})+$/i"
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $pharmacy=Pharmacy::where('id',$id)->first();
        $pharmacy->name=$req->name;
        $pharmacy->address=$req->address;
        $pharmacy->phone_no=$req->phone_no;
        $pharmacy->update();

        return response()->json(
            [
                "msg"=>"Update successfull",
                "data"=>$pharmacy
            ]);
    }  
    function deletePharmacy($id){
        $pharmacy=Pharmacy::where('id',$id)->first();
        $pharmacy->status="inactive";
        $pharmacy->update();
        return response()->json(
            [
                "msg"=>"Delete successfull",
                "data"=>$pharmacy
            ]);
    }
}
