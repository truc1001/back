<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
class CheckChangePassword extends Controller
{
    public function forgetPassword(Request $request) {
        $email = $request->email;
        $checkMail = DB::table('users')->where('email', $email)->first();
        if(!$checkMail) {
            $response = [
                'status' => 201,
                'description' => 'Email không tồn tại'
            ];
            return response()->json($response, 201);
        }else{

            //send mail
            $randomCode = rand(1000,9999);


            $details = [
                'title' => 'Mail from Green Space Solution',
                'body' => 'Enter code: ',
                'code' => $randomCode
            ];
            \Mail::to($email)->send(new \App\Mail\SendMailChangePassword($details));

            //save DB in table password_resets
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $randomCode
            ]);

            $response = [
                'status' => 201,
                'description' => 'Thông tin đổi mật khẩu đã được chuyển về mail, vui lòng kiểm tra hộp thư !',
                'direct' => 'https://mail.google.com/mail/'
            ];

            return response()->json($response, 201);
        }
    }

    public function checkCode(Request $request) {
        $code = $request->code;
        $checkCode = DB::table('password_resets')->where('token', $code)->first();
        try {
            //code...
            if($checkCode) {
                $res = [
                    'status' => 201,
                    'email' => $checkCode->email,
                    'des' => 'Submit code success'
                ];
                return response()->json($res, 201);
            }else {
                $res = [
                    'status' => 401,
                    'des' => 'Error code'
                ];
                return response()->json($res, 401);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $res = [
                'status' => 500,
                'des' => 'Cannot connection',
                'info_error' => $th
            ];
            return response()->json($res, 500);
        }
    }

    public function changePassword(Request $request) {
        
        $password = $request->password;
        $password_confirm = $request->password_confirm;
        if($password == $password_confirm) {
            DB::table('users')->where('email', $request->email)->update([
                'password' => bcrypt($password)
            ]);

            DB::table('password_resets')->where('email', $request->email)->delete();

            $res = [
                'status' => 201,
                'des' => 'Change password success'
            ];
            return response()->json($res, 201);
        }else {
            $res = [
                'status' => 401,
                'des' => 'two passwords are not the same'
            ];
            return response()->json($res, 401);
        }
    }

}
