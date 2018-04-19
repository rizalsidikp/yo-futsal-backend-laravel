<?php

namespace App\Http\Controllers\Api;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BookingController extends Controller
{
    public $successStatus = 200;
    private $booking;

    public function __construct(App\Booking $booking)
    {
        $this->booking = $booking;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking = $this->booking->with(['schedule_detail'])->get();
        $success['message'] =  "Succesfully show all booking";
        $success['booking'] =  $booking;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = $this->booking->with(['schedule_detail'])->find($id);
        $success['message'] =  "Succesfully show booking";
        $success['booking'] =  $booking;
        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $booking = $this->booking->find($id);
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=> "failed", 'message' => $validator->errors()], 400);            
            }
            
            $booking['status'] = $request->status;

            if ($request->hasFile('photo')) {
                $path = $request->photo->store('public/images');
                $booking['photo'] = str_replace('public/', '',$path);
            }
            

            if($booking->save()){
                $booking = $this->booking->find($id);
                if($booking['photo']){
                    $booking['photo'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/'.$booking['photo'];
                }
                $success['message'] =  "Succesfully update your booking";
                $booking->field;
                $success['user'] =  $booking;
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
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
