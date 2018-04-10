<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DetailTeam;
use App\User;
use App\Team;
use Validator;

class DetailTeamController extends Controller
{

    public $successStatus = 200;
    private $detail_team;
    private $user;
    private $team;

    public function __construct(DetailTeam $detail_team, User $user, Team $team)
    {
        $this->detail_team = $detail_team;
        $this->user = $user;
        $this->team = $team;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
                'team_id' => 'required',
                'email' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            if(!($this->team::find($request->team_id)->first())){
                return response()->json(['error'=> "failed", 'message' => "Team not found"], 404);            
            }
            $findUser = $this->user::where('email', $request->email)->first();
            if($findUser){
                if($this->detail_team::where([['team_id',$request->team_id],['user_id', $findUser->id]])->first()){
                    return response()->json(['error'=> "failed", 'message' => "This user already join to this team"], 409);            
                }else{
                    $detail_team = array(
                        'team_id' => $request->team_id,
                        'user_id' => $findUser->id
                    );
                    if($this->detail_team->create($detail_team)){
                        $success['message'] =  "Succesfully add your member team";
                        $success['detail_team'] =  $detail_team;
                        return response()->json(['success' => $success], $this->successStatus);
                    }else{
                        return response()->json(['error'=>'failed', 'message'=>'Failed to update your profile'], 500);                
                    }
                }
            }else{
                return response()->json(['error'=> "failed", 'message' => "User not found"], 404);            
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
    }

    public function myTeam()
    {
        $id = Auth::user()->id;
        $team = $this->detail_team::where('user_id', $id)->orderBy('owner', 'desc')->orderBy('created_at', 'desc')->get();
        $success['message'] =  "Succesfully show your team";
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
        $user_app = Auth::user();        
        if($this->detail_team::where([['user_id',$user_app->id],['team_id',$id],['owner',1]])->first()){
            if($user_app->id == $request->user_id){
                return response()->json(['error'=> "failed", 'message' => "You are not allowed to change your owner status"], 401);                
            }
            $user_change = $this->detail_team::where([['user_id', $request->user_id],['team_id', $id]])->first();
            if($user_change){
                $user_change['owner'] = !$user_change->owner;
                if($user_change->save()){
                    $success['message'] =  "Succesfully edit your member owner status";
                    $success['member'] =  $user_change;
                    return response()->json(['success' => $success], $this->successStatus);
                }else{
                    return response()->json(['error'=>'failed', 'message'=>'Failed to edit your member owner status'], 500);                
                }
            }else{
                return response()->json(['error'=> "failed", 'message' => "Member not found"], 404);            
            }
        }else{
            return response()->json(['error'=> "failed", 'message' => "Not Allowed"], 401);            
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
        $delete_user = $this->detail_team::find($id);
        $user_app = Auth::user();                
        if($this->detail_team::where([['user_id',$user_app->id],['team_id',$delete_user->team_id],['owner',1]])->first()){
            if($user_app->id == $delete_user->user_id){
                return response()->json(['error'=> "failed", 'message' => "You are not allowed to delete yourself"], 401);                
            }
            if($delete_user->delete()){
                $success['message'] =  "Succesfully delete your member";
                $success['member'] =  $delete_user;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to edit your member owner status'], 500);                
            }
        }else{
            return response()->json(['error'=> "failed", 'message' => "Not Allowed"], 401);                        
        }
    }
}
