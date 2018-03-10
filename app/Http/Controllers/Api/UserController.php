<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'c_password' => 'required|same:password',
                'scope' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 401);            
            }

            if($check = DB::table('users')->select('email')->where('email', $request['email'])->get()->first()){
                return response()->json(['error'=> "failed", 'message' => 'Email '.$request['email'].' already used', 'email' => $request['email']], 401);
            }

            $input = $request->all();
            if ($request->hasFile('photo')) {
                $path = $request->photo->store('public/images');
                $input['photo'] = str_replace('public/', '',$path);
            }
            $input['password'] = bcrypt($input['password']);
            if($user = User::create($input)){
                if($user['photo']){
                    $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
                }
                $success['token'] =  $user->createToken('access_token')->accessToken;
                $user->field;
                $success['user'] =  $user;
                return response()->json(['success'=>$success], $this->successStatus);
            }
            
        } catch (Exception $e) {
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);
        }   
    }

     /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        try {
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                $user = Auth::user();
                if($user['scope'] != request('scope')){
                    return response()->json(['error'=> "failed", 'message' => 'This user cannot login as '.request('scope')], 401);
                }
                if($user['photo']){
                    $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
                }
                $success['token'] =  $user->createToken('access_token')->accessToken;
                $user->field;
                $success['user'] =  $user;
                return response()->json(['success' => $success], $this->successStatus);
            }
            else{
                return response()->json(['error'=> "failed", 'message' =>'Unauthorized'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);
        }  
        
    }

    /**
     * details user
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        try{
            $user = Auth::user();
            if($user['photo']){
                $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
            }
            $user->field;
            return response()->json(['success' => $user], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);
        }  
    }

    //get user by id
    public function show($id)
    {
        try{
            $user = User::find($id);
            if($user['photo']){
                $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
            }
            $user->field;
            return response()->json(['success' => $user], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);
        }  
    }

    public function updateUser(Request $request){
        try{
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 401);            
            }
            
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['birthdate'] = $request->birthdate;
            $user['gender'] = $request->gender;
            $user['phone_number'] = $request->phone_number;

            if ($request->hasFile('photo')) {
                $path = $request->photo->store('public/images');
                $user['photo'] = str_replace('public/', '',$path);
            }
            

            if($user->save()){
                $user = Auth::user();
                if($user['photo']){
                    $user['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$user['photo'];
                }
                $success['message'] =  "Succesfully update your profile";
                $user->field;
                $success['user'] =  $user;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your profile'], 401);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);            
        }
    }

    public function updatePassword(Request $request){
        try{
            if(Auth::check(['email' => request('email'), 'password' => request('password')])){
                $user = Auth::user();
                $validator = Validator::make($request->all(), [
                    'password' => 'required',
                    'new_password' => 'required',
                    'c_password' => 'required|same:new_password',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=> "failed", 'message' => $validator->errors()], 401);            
                }
                $user['password'] = bcrypt($request->new_password);
                if($user->save()){
                    $success['message'] =  "Succesfully update your password";                
                    $user->field;
                    $success['user'] =  $user;
                    return response()->json(['success' => $success], $this->successStatus);
                }else{
                    return response()->json(['error'=>'failed', 'message' => 'Failed update your password'], 401);
                }
            }
            else{
                return response()->json(['error'=> "failed", 'message' => 'Unauthorized'], 401);
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 401);                        
        }
    }
}

