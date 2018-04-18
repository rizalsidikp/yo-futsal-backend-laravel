<?php

namespace App\Http\Controllers\Api;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;


class ScheduleController extends Controller
{
    public $successStatus = 200;
    private $schedule;

    public function __construct(App\Schedule $schedule)
    {
        $this->schedule = $schedule;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule = $this->schedule->with(['team_detail', 'field_detail','opponent_detail'])->whereNull('deleted_at')->whereIn('status',['booking','waiting opponent','match'])->get();
        $success['message'] =  "Succesfully show all schedule";
        $success['schedules'] =  $schedule;
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
                'team_id' => 'required',
                'field_id' => 'required',
                'date' => 'required',
                'time' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            $exist = $this->schedule->where([['field_id', $request->field_id],['date', $request->date],['time', $request->time],['deleted_at', null]])->whereIn('status',['waiting opponent','match','completed'])->get()->first();
            if ($exist) {
                return response()->json(['error'=> "failed", 'message' => 'The schedule already exist'], 400);            
            }
            $schedule = array(
                'team_id' => $request->team_id,
                'field_id' => $request->field_id,
                'opponent_id' => $request->opponent_id,
                'open_opponent' => $request->open_opponent,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'booking'
            );
            if($this->schedule->create($schedule)){
                $success['message'] =  "Succesfully create schedule";
                $success['schedule'] =  $schedule;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to create schedule'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = $this->schedule->with(['team_detail', 'field_detail','opponent_detail'])->find($id);
        $success['message'] =  "Succesfully show all your schedule";
        $success['schedule'] =  $schedule;
        return response()->json(['success' => $success], $this->successStatus);
    }

    public function mySchedule()
    {
        $user = Auth::user();
        $schedule = $this->schedule->with(['team_detail', 'field_detail','opponent_detail'])
        ->join('team','schedule.team_id', '=', 'team.id')
        ->join('detail_team','team.id', '=', 'detail_team.team_id')
        ->where('detail_team.user_id', $user->id)
        ->get();
        $success['message'] =  "Succesfully show all schedule";
        $success['schedule'] =  $schedule;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $schedule = $this->schedule->find($id);
            if(!$schedule){
                return response()->json(['error'=> "failed", 'message'=>`Schedule Not Found`], 404);                
            }
            
            $schedule['status'] = $request->status ? $request->status : $schedule->status;
            $schedule['open_opponent'] = $request->open_opponent ? $request->open_opponent  : $schedule->open_opponent;
            $schedule['opponent_id'] = $request->opponent_id;
            if($schedule->save()){
                $success['message'] =  "Succesfully update your schedule";
                $success['schedule'] =  $schedule;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your schedule'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($this->schedule->destroy($id)){
                $success['message'] =  "Succesfully delete your schedule";
                $success['schedule'] =  $this->schedule;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to delete your schedule'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }
}
