<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Planner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\password;

class AuthController extends Controller
{



    ///////////user register////////////////
    ///
    ///
    public function UserRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' =>['required', 'max:55'],
            'email' =>['email','required','unique:users'],
            'photo' =>['image','mimes:jpeg,png,bmp,jpg,gif,svg'],
            'phone_number' =>['required', 'max:13'],
            'password' =>[
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ]
        ]);


        $image=$request->file('image');
        $user_image = null;
        if($request->hasFile('image')){
            $user_image=time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('image'),$user_image);
            $user_image= 'photo/'.$user_image;
        }


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);


        $user = User::query()->create($input);
        $accessToken =$user->createToken('MyApp',['user'])->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' =>$accessToken
        ]);
    }



    //////////user login////////////////////////////////////////////////
    ///
    ///
    ///
    ///
    ///
    public function UserLogin(Request $request): JsonResponse {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials =request(['email', 'password']);
        $credentials['active'] =1;
        $credentials['deleted_at']= null;
        if(auth()->guard('user')->attempt($request->only('email','password'))){
            config(['auth.guards.api.provider' => 'user']);


            $user = User::query()->select('users.*')->find(auth()->guard('user')->user()['id']);
            $success = $user;
            $success['token'] = $user->createToken('MyApp',['user'])->accessToken;
            return response()->json($success);
        } else {
            return response()->json(['error' => ['Unauthorized']], 401);
        }
    }


    ///////////user logout//////////////////////////
    ///
    ///
    ///
    ///
    public function UserLogout(): JsonResponse
    {
        Auth::guard('user-api')->user()->token()->revoke();
        return response()->json(['success' => 'logged out successfully']);
    }


    //planner register/////////////////////
//////////////////////////
///
///
///
///


    public function PlannerRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' =>['required', 'max:55'],
            'email' =>['email','required','unique:planners'],

            'password' =>[
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'phone_number' =>['required'],
            'address' =>['required'],
            'photo' =>['image','mimes:jpeg,png,bmp,jpg,gif,svg'],

        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $image=$request->file('image');
        $planner_image = null;
        if($request->hasFile('image')){
            $planner_image=time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('image'),$planner_image);
            $planner_image= 'photo/'.$planner_image;
        }

        $planner =Planner::query()->create($input);
        $accessToken =$planner->createToken('MyApp',['planner'])->accessToken;
//        for ($i = 0; $i < count($request->category); $i++) {
//            CategoryExpert::query()->create([
//                'expert_id' => $expert->id,
//                'category_id' => $request->category[$i]
//            ]);
//        }


//        for ($i = 0; $i < count($request->time); $i++) {
//            TimeExpert::query()->create([
//                'expert_id' => $expert->id,
//                'time_id' => $request->time[$i]
//            ]);
//        }

        return response()->json([
            'planner' => $planner,
            'access_token' =>$accessToken
        ]);
    }


    ///////////planner login///////////////////////////
    /// //
    ///
    ///
    ///
    ///
    public function PlannerLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials =request(['email', 'password']);
        if(auth()->guard('planner')->attempt($request->only('email','password'))){
            config(['auth.guards.api.provider' => 'planner']);
            $planner = Planner::query()->select('planners.*')->find(auth()->guard('planner')->user()['id']);
            $success = $planner;
            $success['token'] = $planner->createToken('MyApp',['planner'])->accessToken;
            return response()->json($success);
        } else {
            return response()->json(['error' => ['Unauthorized']], 401);
        }
    }



    ///////////planner logout ///////////////////
    /// /
    /// /
    public function PlannerLogout(): JsonResponse
    {
        Auth::guard('planner-api')->user()->token()->revoke();
        return response()->json(['success' => 'logged out successfully']);
    }
}
