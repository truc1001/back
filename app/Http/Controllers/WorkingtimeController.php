<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\workingtime;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Project;

class WorkingtimeController extends Controller
{

    public function getUser()
    {
        $data = User::select('users.id','users.name')->get()->toArray();
        return response()->json($data);
    }

    public function getManagerWorkingtime($id)
    {
        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->join('projects', 'projects.id', '=' ,'workingtime.id_project')->select('users.id','users.name','projects.project_name', 'workingtime.*')->wherenotnull('check_out')->get()->toArray();

        return response()->json($data);
    }

    public function getWorkingtime()
    {
        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->join('projects', 'projects.id', '=' ,'workingtime.id_project')->select('users.id','users.name','projects.project_name', 'workingtime.*')->wherenotnull('check_out')->get()->toArray();

        return response()->json($data);
    }

    public function createWT(){        
        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->select('users.id','users.name', 'workingtime.*')->get()->toArray();
        return response()->json($data);
    }

    
    public function storeWorkingtime(Request $request)
    {
        $wt = new workingtime();
        $wt->check_in = $request->check_in;
        $wt->check_out = $request->check_out;
        $wt->work = $request->work;
        $wt->note = $request->note;
        $wt->id_user = $request->id_user;
        $wt->id_project = $request->id_project;
        $wt->save();
    }

    public function showWorkingtime()
    {

        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->join('projects', 'projects.id', '=' ,'workingtime.id_project')->select('users.id','users.name','projects.project_name', 'workingtime.*')->wherenull('check_out')->get()->toArray();

        return response()->json($data);
    }

    public function editWorkingtime($id)
    {
        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->select('users.id','users.name','users.phone_number','users.email', 'workingtime.*')->where('workingtime.id', $id)->get()->toArray();
        return response()->json($data);
    }
    

    public function updateWorkingtime(Request $request, $id)
    {
        $data = workingtime::find($id);
        $data->check_out = $request->check_out;
        $data->note = $request->note;
        $data->save();
        return reponse()->json($data);
    }

    
    public function destroy($id)
    {
        //
    }
}
