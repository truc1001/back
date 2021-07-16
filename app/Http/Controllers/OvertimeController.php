<?php
// Le_Van_Son

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use App\Models\User;
use App\Models\workingtime;
use App\Models\day_off;

use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use Excel;
use Maatwebsite\Excel\Concerns\FromArray;

//  pass data from the controller to your export
class InvoicesExport implements FromArray
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }
}

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
        $data = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->join('users as admin', 'admin.id', '=' ,'overtime.id_Admin')->select('users.name as name_user', 'admin.name as name_admin', 'overtime.ngayDK',
        'overtime.reason', 'overtime.id', 'overtime.id_user', 'overtime.id_Admin', 'overtime.number', 'overtime.approved_time')->where('status',1)->get()->toArray();
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
        $data1 = Overtime::join('users', 'users.id', '=' ,'overtime.id_user')->join('users as admin', 'admin.id', '=' ,'overtime.id_Admin')->select('users.id','users.name as name_user','admin.name as name_admin', 'overtime.*')->where('status',1)->where('overtime.id_user',$id)->get()->toArray();
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

    // lập báo cáo tổng hợp hằng tháng của nhân viên - chọn lọc - export excel
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


    // public function excel(Request $req){
    //     $dataExcel [] = array('STT','Id_User','Name','Tong_So_Ngay_Nghi_Phep','Tong_So_Gio_Di_Lam',
    //     'Tong_So_Gio_Lam_Them_Vao_Ngay_Thuong','Tong_So_Gio_Lam_Them_Vao_Ngay_Thu_7',
    //     'Tong_So_Gio_Lam_Them_Vao_Ngay_Chu_Nhat','Tong_Cong_So_Gio_Lam_Them');

    //     foreach ($req->toArray() as $key => $v) {
    //         $dataExcel [] = array(
    //             'STT' => (string) ($key + 1),
    //             'Id_User' => (string) $v['id'],
    //             'Name' => $v['name'],
    //             'Tong_So_Ngay_Nghi_Phep' => (string) $v['sumOff'],
    //             'Tong_So_Gio_Di_Lam' => (string) $v['TongGio'],
    //             'Tong_So_Gio_Lam_Them_Vao_Ngay_Thuong' => (string) ($v['sumT'] - ($v['sumT7'] + $v['sumCN'])),
    //             'Tong_So_Gio_Lam_Them_Vao_Ngay_Thu_7' => (string) $v['sumT7'],
    //             'Tong_So_Gio_Lam_Them_Vao_Ngay_Chu_Nhat' => (string) $v['sumCN'],
    //             'Tong_Cong_So_Gio_Lam_Them' => (string) $v['sumT'],
    //         );
    //     }
    //     return response()->json($dataExcel);

    //     // $export = new InvoicesExport($dataExcel);
    //     // return Excel::download($export ,"file_Export_Report.xlsx");
    // }


}
