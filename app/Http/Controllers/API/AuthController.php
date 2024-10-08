<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){
          $validateUser = Validator::make(
            $request->all(),
            [
              'name' => 'required',
              'email' => 'required|email|unique:users,email',
              'password' => 'required',
            ]

            );

            if($validateUser->fails()){
              return response()->json([
                  'status' => false,
                  'massage' => 'Validation Error',
                  'errors' =>$validateUser->error()->all()
              ],401);
            }
          
            $user =User::create([
              'name' => $reques->name,
              'email' => $request->email,
              'password' => $request->password,
            ]);
            return response()->json([
              'status' => true,
              'massage' => 'User Created Successfully',
              'user' => $user,
            ],200);
    }

    public function login(Request $request){
      $validateUser = Validator::make(
        $request->all(),
        [
          'name' => 'required',
          'password' => 'required',
        ]
        );
        if($validateUser->fails()){
          return response()->json([
              'status' => false,
              'massage' => 'Athentication faild',
              'errors' =>$validateUser->error()->all()
          ],404);
        }
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
          $suthUser = Auth::user();
          return response()->json([
            'status' => true,
            'massage' => 'user login successfully',
            'token' => $authUser->createToken("API Token")->plainTextToken,
            'token_type' => 'bearer'
           ],200);
 
        }else{
          return response()->json([
            'status' => false,
            'massage' => 'Email & Password does not matched.',
        ],401);

        }
    }
    


    public function logout(Request $request){
          $user =$request->user();
          $user->tokens()->delete();

          return respons()->json([
              'status' => true,
              'user' => $user,
               'message' => 'You logged out Successfully',
          ], 200);
        
    }
}
