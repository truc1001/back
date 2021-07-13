<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use App\Models\User;
use Session;
use Carbon\Carbon;


class OvertimeController extends Controller
{
    // Lấy DL của Overtime có 'status'=0-> chưa duyệt
    public function getdata0(){        
        $data = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.id','users.name', 'overtime.*')->where('status',0)->get()->toArray();
        return response()->json($data);
    }


    // Lấy DL của Overtime có 'status'=1-> đã duyệt
    public function getdata1(){
        $data = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->select('users.id','users.name', 'overtime.*')->where('status',1)->get()->toArray();
        return response()->json($data);
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
        
        // $data->save();
        return response()->json($data);  
    }


    // xóa dữ liệu OT theo id 
    public function destroyOT($id)
    {
        $data = Overtime::find($id);
        $data->delete();

        return response()->json($data); 
    }

    // lập báo cáo cho OT
    public function getReport(){

        $data = User::leftJoin('overtime', 'overtime.id_user', '=', 'users.id')->SELECT('overtime.id_user','ngayDK')->Dayofweek(date('overtime.ngayDK'))->where('status','0')->get()->toArray();
      
        return response()->json($data);
    }

    
}
