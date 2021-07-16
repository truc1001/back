<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\ReportProject;
use App\Models\workingtime;
use App\Models\User;
use App\Models\Project;

class ReportProjectController extends Controller
{
    public function getReportProjectManager($id)
    {
        $data = ReportProject::join('users', 'users.id', '=' ,'reports.id_user')->join('projects', 'projects.id', '=' ,'reports.id_project')->select('users.id','users.name','projects.*', 'reports.*')->where('projects.id',$id)->get()->toArray();

        return response()->json($data);
    }

    public function getReportProject()
    {
        $data = ReportProject::join('users', 'users.id', '=' ,'reports.id_user')->join('projects', 'projects.id', '=' ,'reports.id_project')->select('users.id','users.name','projects.project_name', 'reports.*')->get()->toArray();

        return response()->json($data);
    }


    public function createReportProject()
    {
        $data = ReportProject::join('users', 'users.id', '=' ,'reports.id_user')->select('users.id','users.name', 'reports.*')->get()->toArray();
        return response()->json($data);
    }

    public function storeReportProject(Request $request)
    {
        $rp = new ReportProject();
        $rp->name_project = $request->name_project;
        $rp->time_for_project = $request->time_for_project;
        $rp->rate_of_process = $request->rate_of_process;
        $rp->status = $request->status;
        $rp->reason = $request->reason;
        $rp->advantage = $request->advantage;
        $rp->disadvantage = $request->disadvantage;
        $rp->suggestion = $request->suggestion;
        $rp->plan_for_next_day = $request->plan_for_next_day;
        $rp->id_user = $request->id_user;
        $rp->id_project = $request->id_project;
        $rp->save();
    }


    public function showReportProject()
    {
        $data = ReportProject::join('users', 'users.id', '=' ,'reports.id_user')->join('projects', 'projects.id', '=' ,'reports.id_project')->select('users.id','users.name','projects.project_name', 'reports.*')->get()->toArray();

        return response()->json($data);
    }


    public function editReportProject($id)
    {
        $data = ReportProject::join('users', 'users.id', '=' ,'reports.id_user')->select('users.id','users.name', 'reports.*')->where('reports.id_report', $id)->get()->toArray();
        return response()->json($data);
    }


    public function updateReportProject(Request $request, $id)
    {
        $data = ReportProject::where('id_report', $id)->first();

        $data->name_project = $request->name_project;
        $data->time_for_project = $request->time_for_project;
        $data->rate_of_process = $request->rate_of_process;
        $data->status = $request->status;
        $data->reason = $request->reason;
        $data->advantage = $request->advantage;
        $data->disadvantage = $request->disadvantage;
        $data->suggestion = $request->suggestion;
        $data->plan_for_next_day = $request->plan_for_next_day;
        $data->id_user = $request->id_user;
        $data->id_project = $request->id_project;

        $data->save();
        return response()->json($data);
    }


    public function destroyReportProject($id)
    {
        $data = ReportProject::find($id);
        $data->delete();
        return response()->json($data);
    }
}
