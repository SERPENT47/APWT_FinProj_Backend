<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Drug;

use Illuminate\Http\Request;

class DrugController extends Controller
{
    function getDrugs(){
        $drugs=Drug::whereNull('status')->get();
        return response()->json($drugs);
    }
    function addDrugs(Request $req){
        $validator = Validator::make($req->all(),[
            "name"=>"required|unique:drugs,name",
            "formula"=>"required",
            "price"=>"required|numeric",
            "available"=>"required|numeric",
        ],
        [
            "name.required"=>"Please provide drugs name",
            "name.unique"=>"Already exists"
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $drug=new Drug();
        $drug->name=$req->name;
        $drug->formula=$req->formula;
        $drug->price=$req->price;
        $drug->available=$req->available;
        $drug->save();
        return response()->json(
            [
                "msg"=>"Drugs added successfully",
                "data"=>$drug
            ]);
    }
    function updateDrugs(Request $req,$id){
        $validator = Validator::make($req->all(),[
            "price"=>"required|numeric",
            "available"=>"required|numeric",
        ]
        );
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $drugs=Drug::find($id);
        $drugs->price=$req->price;
        $drugs->available=$drugs->available+$req->available;
        $drugs->update();
        return response()->json(
            [
                "msg"=>"Price and availability updated successfully",
                "data"=>$drugs
            ]);
    }
    function deleteDrugs($id){
        $drugs=Drug::where('id',$id)->first();
        $drugs->status="delete";
        $drugs->update();
        return response()->json(
            [
                "msg"=>"Drugs delete successfully",
                "data"=>$drugs
            ]);
    }
}
