<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
//            return $request->all();
            $user = Auth::user();
            if ($user) {

                $validator = Validator::make($request->all(), [
                    'group_name' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }
                $requestData = $request->all();
                $requestData['user_id'] = Auth::user()->id;
                $groupDetailsData = Message::create($requestData);
                if ($groupDetailsData) {
                    return response()->json([
                        'status' => true,
                        'message' => "Message Created Successfully",
                    ], 200);

                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Something Is Wrong To Create Form",
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


    public function delete(Request $request)
    {
        $message = Message::where('id', $request['id'])->where('group_id', $request['group_id'])->first();
        if (!isset($message))
        {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 200);
        }
        $message->delete();
        if ($message)
        {
            return response()->json([
                'status' => true,
                'message' => 'Message Deleted Successfully',
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Something is went wrong to delete',
            ], 200);
        }
    }

}
