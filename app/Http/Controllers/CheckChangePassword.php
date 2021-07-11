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

            //nhớ sửa cái link
            $details = [
                'title' => 'Mail from Green Space Solution',
                'body' => 'Enter code: ',
                'code' => $randomCode,
                'link' => 'em tự nhập vào đây nhé'
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

}
