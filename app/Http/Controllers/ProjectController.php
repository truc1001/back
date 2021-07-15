<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Session;
use Carbon\Carbon;

class ProjectController extends Controller
{
    
   //lấy project chưa hoàn thành
    public function getProject()
    {
        $data = Project::select('projects.id','projects.project_name')->where('status',0)->get()->toArray();
        return response()->json($data);
    }

    //lưu project
    public function storeProject(Request $request)
    {
        $pj = new Project();
        $pj->project_name = $request->project_name;
        $pj->detail = $request->detail;
        $pj->status = $request->status;
        $pj->receipt_date = $request->receipt_date;
        $pj->finish_data = $request->finish_data;
        $pj->id_user = $request->id_user;
        $pj->save();
    }

    //hiển thị project
    public function showProject()
    {
        $data = Project::join('users','users.id','=','projects.id_user')->select('users.id','users.name','projects.*')->where('status',0)->get()->toArray();
        return response()->json($data);
    }
    //hiển thị project đã hoàn thành
    public function showFinishedProject()
    {
        $data = Project::join('users','users.id','=','projects.id_user')->select('users.id','users.name','projects.*')->where('status',1)->get()->toArray();
        return response()->json($data);
    }

    //sửa project
    public function editProject($id)
    {
        $data = Project::join('users','users.id','=','projects.id_user')->select('users.id','users.name','projects.*')->where('projects.id', $id)->get()->toArray();
        return response()->json($data);
    }

    //update projects
    public function updateProject(Request $request, $id)
    {
        $data = Project::find($id);
        $data->project_name = $request->project_name;
        $data->detail = $request->detail;
        $data->status = $request->status;
        $data->receipt_date = $request->receipt_date;
        $data->finish_data = $request->finish_data;
        $data->id_user = $request->id_user;
        $data->save();
        return reponse()->json($data);
    }
    //check đã hoàn thành dụ án
    public function finishProject($id){

        $data = Project::find($id); 
        $data->status = '1';
        $ngay = Carbon::now();
        $data->finish_data = $ngay->toDateTimeString();        
        
        $data->save();
        return response()->json($data);  
    }

    //xóa project
    public function destroyProject($id)
    {
        $data = Project::find($id);
        $data->delete();
        return response()->json($data); 
    }
}
