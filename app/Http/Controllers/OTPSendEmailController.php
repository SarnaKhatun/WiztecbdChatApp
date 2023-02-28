<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class OTPSendEmailController extends Controller
{
    public function sendOtp(Request $request)
    {
        $otp = rand(100, 10000);
        Log::info("otp =" . $otp);

        $user = User::where('email', $request['email'])->update(['otp' => $otp]);

        if ($user) {
            $mail = Mail::to($request['email'])->send(new OTPMail([
                'otp' => $otp,
            ]));

            if ($mail) {
                return response()->json([
                    'status' => true,
                    'message' => 'OTP send Successfully',

                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP Not Send',
                ], 200);
            }
        }


    }



    public function checkOtp(Request $request)
    {
        $user = User::where('email', $request['email'])->where('otp', $request['otp'])->first();

        if ($user)
        {
//            Auth::login($user, true);
//            User::where('email', $request['email'])->update(['otp' => null]);
            $accessToken = auth()->user()->createToken('auth_token')->accessToken;

            if (!$accessToken)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP not Matched',
                ], 200);
            }
            else{
                return response()->json([

                    'status' => true,
                    'message' => 'Matched OTP',
                    'user' => Auth::user()->name,
                    'access_token' => $accessToken,
                ], 200);
            }

        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Invalid token",
            ], 200);
        }
    }
}
