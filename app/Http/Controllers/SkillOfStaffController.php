<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\language_skill;
use App\Models\programming_skill;
use App\Models\skillOfStaff;

use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class SkillOfStaffController extends Controller
{

    public function create(Request $request)
    {
        $kt = skillOfStaff::where('id_user','=',$request->id_user)->get(['id_user', 'id'])->toArray();
        if($kt){
            return response()->json($kt);
        }
        else{
            $PS = programming_skill::select('id','namePS')->get()->toArray();
            $LS = language_skill::select('id','nameLS')->get()->toArray();
            $data = [$PS,$LS];
            return response()->json($data);
        }

    }


    public function store(Request $request)
    {
        $data = new skillOfStaff();
        $data->id_user = $request->id_user;
        $data->language_skills = implode(";",$request->language_skills);
        $data->degree = $request->degree;
        $temp =  explode(';',implode(";",$request->level));
        for($i=0 ; $i < count($temp); $i++) {
            $temp[$i] = $request->programming_skills[$i].'/'.$temp[$i];
        }
        $data->programming_skills =  implode(";",$temp);
        $data->save();
        return response()->json('Thanh Cong');
    }

    public function edit($id)
    {
        $d = skillOfStaff::join('users', 'users.id', '=' ,'skillofstaff.id_user')->select('id_user','email','phone_number',
        'name','programming_skills','language_skills','degree')->where('skillofstaff.id','=',$id)->get()->toArray();

        $PS = programming_skill::select('id','namePS')->get()->toArray();
        $LS = language_skill::select('id','nameLS')->get()->toArray();
        $data = [$d, $PS, $LS];

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = skillOfStaff::find($id);
        $data->language_skills = implode(";",$request->language_skills);
        $data->degree = $request->degree;
        $temp =  explode(';',implode(";",$request->level));
        for($i=0 ; $i < count($temp); $i++) {
            $temp[$i] = $request->programming_skills[$i].'/'.$temp[$i];
        }
        $data->programming_skills =  implode(";",$temp);
        $data->save();
        return response()->json($data);
    }
    public function show()
    {
        $d = skillOfStaff::join('users', 'users.id', '=' ,'skillofstaff.id_user')->select('skillofstaff.id','id_user',
        'name','programming_skills','language_skills','degree')->get()->toArray();

        $PS = programming_skill::select('namePS as name')->get()->toArray();
        $LS = language_skill::select('nameLS as name')->get()->toArray();
        $data = [$d, $PS, $LS];

        return response()->json($data);
    }


    public function searchSkill(Request $request)
    {
        $d = skillOfStaff::join('users', 'users.id', '=' ,'skillofstaff.id_user')->select('skillofstaff.id','id_user',
        'name','programming_skills','language_skills','degree')->where('programming_skills',
        'LIKE','%'.$request->keyword.'%')->orWhere('language_skills',
        'LIKE','%'.$request->keyword.'%')->get()->toArray();

        $PS = programming_skill::select('namePS as name')->get()->toArray();
        $LS = language_skill::select('nameLS as name')->get()->toArray();
        $data = [$d, $PS, $LS];

        return response()->json($data);
    }

    //delete
    public function getDelete($id)
    {
        $data = skillOfStaff::find($id);
        $data->delete();
        return response()->json($data);

    }

}
