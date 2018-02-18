<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App;
use Illuminate\Support\Facades\DB;
use Validator;


class FieldController extends Controller
{
    public $successStatus = 200;


    public function updateMyField(Request $request)
    {
        try{
            $user = Auth::user();
            $field = $user->field;
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'unique:field',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 401);            
            }
            $field['name'] = $request->name;
            $field['email'] = $request->email;
            $field['phone_number'] = $request->phone_number;
            $field['city'] = $request->city;
            $field['address'] = $request->address;
            $field['description'] = $request->description;
            if ($request->hasFile('photo')) {
                $path = $request->photo->store('public/images/field');
                $field['photo'] = str_replace('public/', '',$path);
            }
            
            if($field->save()){
                $user = Auth::user();
                $user->field;
                if($user['photo']){
                    $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
                }
                if($user->field['photo']){
                    $user->field['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
                }
                $success['message'] =  "Succesfully update your profile";
                $success['user'] =  $user;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your field profile'], 401);                
            }
            return response()->json(['success' => $user], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['error'=> "Failed", 'message' => $e.getMessage() ], 401);
        }  
    }
}
