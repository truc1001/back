<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
/*NOTE*/
//Sửa migrate mà k chịu fresh
//Sai chính tả string =)
// sai chính tả username

class AuthController extends Controller
{
    /**
     *Create a new AuthController instance.
     *
     *@return void
     */
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){
        // $validator = Validator::make($request->all(),[
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]); //:(hihi

        // if($validator->fails()){
        //     return response()->json($validator->error(), 422);
        // }
        // $user = [
        //     'email' => 'phuongne@gmail.com',
        //     'password' => 'Phuongne123'
        // ];
        $user = $request->all();
        if(!$token = auth()->attempt($user)){
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     *Register a User.
     *
     *@return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        //sai chính tả=)) sr a
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'id_type' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        //truyền vào form body theo các biến như trên

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' =>$user
        ], 201);
    }

    /**
     *Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(){
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
