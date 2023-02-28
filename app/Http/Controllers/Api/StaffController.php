<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function staffList()
    {
        // is login
        // is admin
        // get staff list

        $user = Auth::user();

        if ($user) {

            if ($user->role != 1){
                return response()->json([
                    'status' => false,
                    'message' => "Your are not an admin",
                ], 200);
            }

            $staffList = User::where('role', 3)->select('id', 'name', 'email', 'phone', 'profile_image', 'role')->latest()->paginate();

            if (count($staffList) > 0) {
                return response()->json([
                    'status' => true,
                    'lists' => $staffList,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Staff Lists Not Found",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Invalid token",
            ], 200);
        }


    }



    public function create(Request $request)
    {

        if($request->isMethod('post'))
        {
            $user= Auth::user();
            if ($user)
            {
                if ($user->role != 1)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are not an admin',
                    ], 200);
                }
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }
                $userData = new User();
                $userData->name = $request['name'];
                $userData->email = $request['email'];
                $userData->password = bcrypt($request['password']);
                $userData->password = bcrypt($request['password']);
                $userData->role = 3;
                $userData->save();
                if ($userData) {
                    return response()->json([
                        'status' => true,
                        'message' => "Staff Created Successfully",
                    ], 200);

                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Something Is Wrong To Create Staff",
                    ], 200);

                }
            }
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Invalid Token",
            ], 200);
        }
    }


    public function update(Request $request)
    {
        if($request->isMethod('post'))
        {
            $user= Auth::user();
            if ($user)
            {
                if ($user->role != 1)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are not an admin',
                    ], 200);
                }

                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }


                $profile_image = time() . '.' . $request->profile_image->extension();
                $request->profile_image->move(public_path('images/profile_image'), $profile_image);


                $userData = User::where('id', $request['id'])->where('role', 3)->first();
                $userData->name = $request['name'];
                $userData->phone = $request['phone'];
                $userData->otp = $request['otp'];
                $userData->profile_image = $_SERVER['HTTP_HOST'] . '/public/images/profile_image/' . $profile_image;;
                $userData->save();
                if ($userData) {
                    return response()->json([
                        'status' => true,
                        'message' => "Staff updated Successfully",
                    ], 200);

                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Something Is Wrong To update Staff",
                    ], 200);

                }
            }
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Invalid Token",
            ], 200);
        }
    }



    public function delete(Request $request)
    {
        $user = Auth::user();
        if ($user)
        {
            if ($user->role != 1)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not an Admin',
                ], 200);
            }

            $clientDelete = User::where('id', $request['id'])->where('role', 3)->first();
            if (!isset($clientDelete))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found',
                ], 200);
            }

            $clientDelete->delete();

            if ($clientDelete)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Staff Deleted Successfully',
                ], 200);
            }
            else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something is went wrong to delete',
                ], 200);
            }
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Invalid Token",
            ], 200);
        }



    }
}
