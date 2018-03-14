<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Team;
use Validator;

class TeamController extends Controller
{
    public $successStatus = 200;
    private $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }      

    public function generateTeamId(){
        $prefix = 'YOF';
        $characters = 'QWERTYUIOPASDFGHJKLZXCVBNM0123456789';
        $max = strlen($characters) - 1;
        $generator = '';
        do{
            for ($i = 0; $i < 7; $i++) {
                $generator .= $characters[mt_rand(0, $max)];
            }
            $generator = $prefix.$generator;
        }while(DB::table('team')->select('id')->where('id', $generator)->get()->first());
        return $generator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $team = Team::with(['detail_team', 'detail_team.user_detail'])->get();
        $success['message'] =  "Succesfully show all team";
        $success['team'] =  $team;
        return response()->json(['success' => $success], $this->successStatus);
    }

    public function myTeam()
    {
        $user = Auth::user()->id;
        $team = Team::with(['detail_team', 'detail_team.user_detail'])->where('user_id', $user)->get();
        $success['message'] =  "Succesfully show all team";
        $success['team'] =  $team;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'team_name' => 'required',
                'team_city' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            $user = Auth::user();
            $team_id = $this->generateTeamId();
            $teams = array(
                'id' => $team_id,
                'team_name' => $request->team_name,
                'team_city' => $request->team_city,
                'user_id' => $user->id
            );
            if($this->team->create($teams)){
                $success['message'] =  "Succesfully create your team";
                $success['team'] =  $teams;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your profile'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $team = Team::with(['detail_team', 'detail_team.user_detail'])->find($id);
        $success['message'] =  "Succesfully show team";
        $success['team'] =  $team;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $validator = Validator::make($request->all(), [
                'team_name' => 'required',
                'team_city' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            $user = Auth::user();
            $team = $this->team->find($id);
            if($user['id'] != $team['user_id']){
                return response()->json(['error'=> "failed", 'message'=>`You can't edit this team`], 401);                
            }
            
            $team['team_name'] = $request->team_name;
            $team['team_city'] = $request->team_city;
            if($team->save()){
                $success['message'] =  "Succesfully edit your team";
                $success['team'] =  $team;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your profile'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($this->team->destroy($id)){
                $success['message'] =  "Succesfully delete your team";
                $success['team'] =  $this->team;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your profile'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }
}
