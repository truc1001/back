<?php
// Le_Van_Son

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use App\Models\User;
use Session;
use Carbon\Carbon;

use App\Models\workingtime;
use App\Models\day_off;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OvertimeController extends Controller
{
    // Lấy DL của Overtime có 'status'=0-> chưa duyệt
    public function getdata0(){
        $data = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.name', 'overtime.ngayDK',
        'overtime.reason', 'overtime.id', 'overtime.id_user', 'overtime.number', 'overtime.status')->where('status',0)->get()->toArray();
        return response()->json($data);
    }


    // Lấy DL của Overtime có 'status'=1-> đã duyệt
    public function getdata1(){
        $data1 = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.name', 'overtime.ngayDK',
        'overtime.reason', 'overtime.id', 'overtime.id_user', 'overtime.id_Admin', 'overtime.number', 'overtime.approved_time')->where('status',1)->get()->toArray();

        $data2 = Overtime::join('users', 'users.id', '=' ,'overtime.id_Admin')->select('users.id','name')->where('status',1)->groupBy('users.id','name')->orderBy('users.id', 'asc')->get()->toArray();

        $d = [$data1, $data2];

        return response()->json($d);
    }


    // Lưu DL từ form đăng ký vào bảng Overtime
    public function storeOT(Request $request)
    {
        $ot = new Overtime();
        $ot->id_user = $request->id_user;
        $ot->reason = $request->reason;
        $ot->number = $request->number;
        $ot->ngayDK = $request->ngayDK;
        $ot->save();
    }


    // Show OT theo id của user
    public function showOT($id)
    {
        $data0 = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.id','users.name', 'overtime.*')->where('status',0)->where('overtime.id_user',$id)->get()->toArray();
        $data1 = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.id','users.name', 'overtime.*')->where('status',1)->where('overtime.id_user',$id)->get()->toArray();
        $d = [$data0, $data1];

        return response()->json($d);
    }


    // Lấy dữ liệu form đăng ký của "User" và hiển thị
    public function editOT($id)
    {
        $data = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.id','users.name','users.phone_number','users.email', 'overtime.*')->where('overtime.id', $id)->get()->toArray();
        return response()->json($data);
    }

    // Cập nhật lại form đăng ký của User
    public function updateOT(Request $request, $id)
    {
        $data = Overtime::find($id);
        $data->ngayDK = $request->ngayDK;
        $data->reason = $request->reason;
        $data->number = $request->number;
        $data->save();
        return response()->json($data);
    }


    // Duyệt đơn đăng ký của User -> thay đổi thuộc tính 'status'
    public function approveOT(Request $req , $idOT){

        $data = Overtime::find($idOT);
        $data->id_Admin = $req->admin_id;
        $data->status = '1';
        $ngay = Carbon::now();
        $data->approved_time = $ngay->toDateTimeString();

        $data->save();
        return response()->json($data);
    }


    // xóa dữ liệu OT theo id
    public function destroyOT($id)
    {
        $data = Overtime::find($id);
        $data->delete();

        return response()->json($data);
    }

    // lập báo cáo cho users
    public function getReport(){

        $data = User::select('id','name')->orderBy('id', 'asc')->get();

        $data0 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where('status','1')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(overtime.number) AS sumT')]);

        $data1 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where(DB::raw('DAYOFWEEK(ngayDK)'),'7')->where('status','1')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(number) AS sumT7')]);

        $data2 = User::leftjoin('overtime','overtime.id_user', '=' ,'users.id')->where(DB::raw('DAYOFWEEK(ngayDK)'),'1')->where('status','1')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(number) AS sumCN')]);

        $workingtime =  User::leftjoin('workingtime','workingtime.id_user', '=' ,'users.id')->groupBy('users.id')->orderBy('users.id', 'asc')->get(['users.id', DB::raw('SUM(HOUR(workingtime.check_out - workingtime.check_in)) as TongGio')]);

        $dayOff = User::leftJoin('day_off', 'day_off.user_id', '=', 'users.id')->where('status','1')->groupBy('users.id')->orderBy('users.id','asc')->get(['users.id', DB::raw('SUM(day_off.num_off) as sum_off')]);

        foreach ($data as $key => $v) {
            $v->sumT = 0;
            $v->sumT7 = 0;
            $v->sumCN = 0;
            $v->sumOff = 0;
            foreach ($data0 as $key0 => $v0) {
                if($v->id == $v0->id){
                    $v->sumT = $v0->sumT;
                    break;
                }
            }
            foreach ($data1 as $key1 => $v1) {
                if($v->id == $v1->id){
                    $v->sumT7 = $v1->sumT7;
                    break;
                }
            }
            foreach ($data2 as $key2 => $v2) {
                if($v->id == $v2->id){
                    $v->sumCN = $v2->sumCN;
                    break;
                }
            }
            foreach ($workingtime as $k => $v3) {
                if($v->id == $v3->id){
                    $v->TongGio = $v3->TongGio;
                    break;
                }
            }
            foreach ($dayOff as $day => $v4) {
                if($v->id == $v4->id){
                    $v->sumOff = $v4->sum_off;
                    break;
                }
            }
        }

        // dd($data->toArray());
        // return $data->toArray();


        return response()->json($data);

    }


}
