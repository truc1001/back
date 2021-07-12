<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\day_off;
use App\Models\User;
use Carbon\Carbon;

class DayoffController extends Controller
{
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
//du lieu nghi phep chua duyet
    public function getData(){
        $data = day_off::join('users', 'users.id', '=', 'day_off.user_id')->select('users.id', 'users.name', 'day_off.*')->where('status',0)->get();
        return response()->json($data);


        }
//du lieu nghi phep da duyet
public function getApproved(){
    $data = day_off::join('users', 'users.id', '=', 'day_off.user_id')->select('users.id', 'users.name', 'day_off.*')->where('status',1)->get();
    return response()->json($data);


    }

// public function getDayOff(){

//     }

//dang ky nghi phep
public function postDayOff(Request $request){

    $day_off = new day_off();
    $day_off->user_id = $request->user_id;
    $day_off->off_reason = $request->off_reason;
    $day_off->start_off = $request->start_off;
    $day_off->num_off = $request->num_off;
    $day_off->save();
//return redirect('dayoff/dayoff_register')->with('thongbao','Đã Yêu Cầu');
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
//show du lieu nghi phep
    public function showDayOff($id)
    {
        $status0 = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->select('users.id','users.name', 'day_off.*')->where('status',0)->where('day_off.user_id',$id)->get();

        $status1 = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->select('users.id','users.name', 'day_off.*')->where('status',1)->where('day_off.user_id',$id)->get();
        $data = [$status0, $status1];

        return response()->json($data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //show form edit nghi phep
    public function editDayOff($id)
    {
        $data = day_off::join('users', 'users.id', '=', 'day_off.user_id')->select('users.id', 'users.name', 'users.phone_number', 'users.email', 'day_off.*')->where('day_off.id', $id)->get();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //update thong tin nghi phep
    public function updateDayOff(Request $request, $id)
    {
        $day_off = day_off::find($id);
        $day_off->off_reason = $request->off_reason;
        $day_off->start_off = $request->start_off;
        $day_off->num_off = $request->num_off;
        $day_off->save();
        return response()->json($day_off);

    }
    //duyet nghi phep
    public function ApprovedDayOff(Request $request, $idOff)
    {
        $data = day_off::find($idOff);
        $data->admin_id = $request->admin_id;
        //$data->name = $request->name;
        $data->status = '1';
        $date_approved = Carbon::now();
        $data->approved_at = $date_approved->toDateTimeString();
        $data->save();
        return response()->json($data);

    }
    //xoa yeu cau nghi phep
    public function getCancel($id)
    {
        $data = day_off::find($id);
        $data->delete();
        return response()->json($data);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
