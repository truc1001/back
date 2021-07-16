<?php
//author by Truc Ho
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\day_off;
use App\Models\User;
use App\Models\Overtime;
use App\Models\workingtime;
use App\Models\reportExcel;
use App\Exports\ExcelExport;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $data = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->select('users.name', 'day_off.start_off',
        'day_off.off_reason', 'day_off.id', 'day_off.user_id', 'day_off.num_off', 'day_off.status')->where('status',0)->get();
        return response()->json($data);


        }
//du lieu nghi phep da duyet
public function getApproved(){
    $data = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->join('users as admin', 'admin.id', '=' ,'day_off.admin_id')->select('users.name as name_user', 'admin.name as name_admin', 'day_off.start_off',
    'day_off.off_reason', 'day_off.id', 'day_off.user_id', 'day_off.admin_id', 'day_off.num_off', 'day_off.approved_at')->where('status',1)->get();
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
        $data0 = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->select('users.id','users.name', 'day_off.*')->where('status',0)->where('day_off.user_id',$id)->get();
        $data1 = day_off::join('users', 'users.id', '=' ,'day_off.user_id')->join('users as admin', 'admin.id', '=' ,'day_off.admin_id')->select('users.id','users.name as name_user','admin.name as name_admin', 'day_off.*')->where('status',1)->where('day_off.user_id',$id)->get();
        $d = [$data0, $data1];

        return response()->json($d);
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
    //admin duyet nghi phep
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
    // Monthly report - choose - export
    public function getReport(Request $req){

        $data = User::select('id','name')->orderBy('id', 'asc')->get();

        $data0 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where('status','1')->whereBetween('approved_time', [$req->DayBegin, $req->DayEnd])->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(overtime.number) AS sumT')]);

        $data1 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where('status','1')->whereBetween('approved_time', [$req->DayBegin, $req->DayEnd])->where(DB::raw('DAYOFWEEK(ngayDK)'),'7')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(number) AS sumT7')]);

        $data2 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where('status','1')->whereBetween('approved_time', [$req->DayBegin, $req->DayEnd])->where(DB::raw('DAYOFWEEK(ngayDK)'),'1')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(number) AS sumCN')]);

        $workingtime =  User::leftjoin('workingtime','workingtime.id_user', '=' ,'users.id')->whereBetween('check_out', [$req->DayBegin, $req->DayEnd])->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(HOUR(workingtime.check_out - workingtime.check_in)) as TongGio')]);

        $dayOff = User::leftJoin('day_off', 'day_off.user_id', '=', 'users.id')->where('status','1')->whereBetween('approved_at', [$req->DayBegin, $req->DayEnd])->groupBy('users.id')->orderBy('users.id','asc')->get(['users.id', DB::raw('SUM(day_off.num_off) as sum_off')]);

        foreach ($data as $key => $v) {
            $v->sumT = 0;
            $v->sumT7 = 0;
            $v->sumCN = 0;
            $v->sumOff = 0;
            $v->TongGio = 0;
            foreach ($data0 as $key0 => $v0) {
                if($v->id == $v0->id){
                    if($v0->sumT){
                        $v->sumT = $v0->sumT;
                        break;
                    }
                }
            }
            foreach ($data1 as $key1 => $v1) {
                if($v->id == $v1->id){
                    if($v1->sumT7){
                        $v->sumT7 = $v1->sumT7;
                        break;
                    }
                }
            }
            foreach ($data2 as $key2 => $v2) {
                if($v->id == $v2->id){
                    if($v2->sumCN){
                        $v->sumCN = $v2->sumCN;
                        break;
                    }
                }
            }
            foreach ($workingtime as $k => $v3) {
                if($v->id == $v3->id){
                    if($v3->TongGio){
                        $v->TongGio = $v3->TongGio;
                        break;
                    }
                }
            }
            foreach ($dayOff as $day => $v4) {
                if($v->id == $v4->id){
                    if($v4->sum_off){
                        $v->sumOff = $v4->sum_off;
                        break;
                    }
                }
            }
            $v->sumNT = $v->sumT - $v->sumT7 -$v->sumCN;
        }
        return response()->json($data);

    }

}
