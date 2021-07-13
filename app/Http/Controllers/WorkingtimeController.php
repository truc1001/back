<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\workingtime;
use App\Models\User;
use Session;
use Carbon\Carbon;

class WorkingtimeController extends Controller
{
    
    public function index()
    {
        //
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
        $wt->save();
    }

    public function showWorkingtime()
    {
        $data = workingtime::join('users', 'users.id', '=' ,'workingtime.id_user')->select('users.id','users.name', 'workingtime.*')->get()->toArray();
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
