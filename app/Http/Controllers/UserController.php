<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUserByAdmin(Request $request){
        $user;
        if (User::where('id', $request->get('creatorId'))->count() == 0){
            return response()->json([
                'FATAL' => "NO SUCH USER",
            ]);
        }
        else {
            $user = User::where('id', $request->get('creatorId'));
        }
        
        if ($user->first()->admin == 1){
            User::create([
                'name' => $request->get("name"),
                'email' => $request->get("email"),
                'password' => Hash::make($request->get("password")),
                'admin' => $request->get('admin'),
            ]);
    
            return response()->json([
                'OPERATION_MESSAGE' => "USER ".User::all()->last()->name." HAS BEEN CREATED.",
                'status' => 'successful'
            ]);
        }
        else {
            return response()->json([
                'FATAL' => "YOU ARE NOT ALLOWED.",
            ]);
        }
        
    }

    public function createUser(Request $request){
        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required",
            "email"             =>          "required|email",
            "password"          =>          "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "OPERATION_MESSAGE" => "validation_error", "errors" => $validator->errors()]);
        }

        User::create([
            'name' => $request->get("name"),
            'email' => $request->get("email"),
            'password' => Hash::make($request->get("password")),
            'admin' => 0,
        ]);

        return response()->json([
            'OPERATION_MESSAGE' => "USER ".User::all()->last()->name." HAS BEEN CREATED.",
            'status' => 'successful'
        ]); 
    }

    public function deleteUser(Request $request){
        if (User::where('id', $request->deleteInitiator)->count() == 0){
            return response()->json([
                'OPERATION_MESSAGE' => "YOU ARE NOT AN ADMIN",
                'status' => 'failed'
            ]); 
        }
        else {
            if (User::where('id', $request->deleteSubject)->count() == 0){
                return response()->json([
                    'OPERATION_MESSAGE' => "NO SUCH USER",
                    'status' => 'failed'
                ]); 
            }
            else {
                User::where('id', $request->deleteSubject)->delete();
                return response()->json([
                    'OPERATION_MESSAGE' => "USER DELETED",
                    'status' => 'successful'
                ]); 
            }
        }
        
    }

    public function loginUser(Request $request){
        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }
        
        if (User::where([
            ['email', '=', $request->get("email")],
            // ['password', '=', Hash::make($request->get("password"))],
        ])->count() != 0){
            $user = User::where("email", $request->get("email"))->first();
            if (Hash::check($request->get("password"), $user->password)){
                $user = $this->userDetail($request->email);
                return response()->json([
                    'OPERATION_MESSAGE' => "USER ".$request->get("email")." LOGIN SUCCESSFUL.",
                    'status' => "successful",
                    'data' => $user,
                ]);
            }
            else {
                return response()->json([
                    'OPERATION_MESSAGE_PWD' => "INCORRECT PASSWORD.",
                    'status' => "failed"
                ]);
            }
            
        }
        else {
            return response()->json([
                'OPERATION_MESSAGE_EML' => "INCORRECT EMAIL.",
                'status' => "failed"
            ]);
        }
            // if (User::where([
            //     // ['name', '=', $request->get("name")],
            //     ['email', '=', $request->get("email")],
            //     ['password', '=', Hash::make($request->get("password"))]
            //     ]){
            //         return "e"
            //     }
    }

    public function getAllUsers(Request $request){
        $user = User::where('id', $request->id)->first();
        if ($user->admin == 1){
            return response()->json([
                'allUsers' => User::where('id', '!=', $user->id)->get(),
            ]);
        }
    }

    public function makeAdmin(Request $request){
        if (User::where('id', $request->get('id_maker'))->count() == 0){
            return response()->json([
                'OPERATION_MESSAGE' => "NO SUCH ADMIN",
                'status' => 'failed',
            ]);
        }
        else if (User::where('id', $request->get('id_receiver'))->count() == 0){
            return response()->json([
                'OPERATION_MESSAGE' => "NO SUCH USER",
                'status' => 'failed',
            ]);
        }
        else {
            $admin = User::where('id', $request->get('id_maker'))->first();
            if ($admin->admin == 1){
                User::where('id', $request->get('id_receiver'))->update([
                    'admin' => 1,
                ]);
            }
            else {
                return response()->json([
                    'OPERATION_MESSAGE' => "YOU ARE NOT AN ADMIN",
                    'status' => 'failed',
                ]);
            }
            
            return response()->json([
                'OPERATION_MESSAGE' => "ADMIN GRANTED",
                'status' => 'successful',
            ]);
        }
    }

    public function removeAdmin(Request $request){
        if (User::where('id', $request->get('id_maker'))->count() == 0){
            return response()->json([
                'OPERATION_MESSAGE' => "NO SUCH ADMIN",
                'status' => 'failed',
            ]);
        }
        else if (User::where('id', $request->get('id_receiver'))->count() == 0){
            return response()->json([
                'OPERATION_MESSAGE' => "NO SUCH USER",
                'status' => 'failed',
            ]);
        }
        else {
            $admin = User::where('id', $request->get('id_maker'))->first();
            if ($admin->admin == 1){
                User::where('id', $request->get('id_receiver'))->update([
                    'admin' => 0,
                ]);
            }
            else {
                return response()->json([
                    'OPERATION_MESSAGE' => "YOU ARE NOT AN ADMIN",
                    'status' => 'failed',
                ]);
            }
            
            return response()->json([
                'OPERATION_MESSAGE' => "ADMIN REMOVED",
                'status' => 'successful',
            ]);
        }
    }

    public function userDetail($email) {
        $user = array();
        if($email != "") {
            $user = User::where("email", $email)->first();
            return $user;
        }
    }
}
