<?php

namespace App\Http\Controllers\Api;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OpponentsController extends Controller
{
    public $successStatus = 200;
    private $opponent;

    public function __construct(App\Opponent $opponent)
    {
        $this->opponent = $opponent;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $opponent = $this->opponent->whereNull('deleted_at')->get();
        $success['message'] =  "Succesfully show all opponent";
        $success['opponents'] =  $opponent;
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
                'schedule_id' => 'required',
                'team_id' => 'required',
                'opponent_id' => 'required',
                'type' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            $opponent = array(
                'schedule_id' => $request->schedule_id,
                'team_id' => $request->team_id,
                'opponent_id' => $request->opponent_id,
                'type' => $request->type,
                'status' => $request->status,
            );
            if($this->opponent->create($opponent)){
                $success['message'] =  "Succesfully create your invitation";
                $success['opponent'] =  $opponent;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to create your invitation'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opponents  $opponents
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $opponent = $this->opponent->find($id);
        $success['message'] =  "Succesfully show all opponent";
        $success['opponent'] =  $opponent;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Opponents  $opponents
     * @return \Illuminate\Http\Response
     */
    public function edit(Opponents $opponents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opponents  $opponents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            $opponent = $this->opponent->find($id);
            
            $opponent['status'] = $request->status;
            if($opponent->save()){
                $success['message'] =  "Succesfully update invitation status";
                $success['opponent'] =  $opponent;
                return response()->json(['success' => $success], $this->successStatus);
            }else{
                return response()->json(['error'=>'failed', 'message'=>'Failed to update your invitation status'], 500);                
            }
        }
        catch(Exception $e){
            return response()->json(['error'=> "failed", 'message' => $e.getMessage() ], 500);            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opponents  $opponents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opponents $opponents)
    {
        //
    }
}
