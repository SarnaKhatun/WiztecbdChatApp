<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageDetailsController extends Controller
{
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
//            return $request->all();
            $user = Auth::user();
            if ($user) {

                $validator = Validator::make($request->all(), [
                    'message_body' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }
                $requestData = $request->all();
                $requestData['user_id'] = Auth::user()->id;
                $messageDetails = MessageDetail::create($requestData);
                if ($messageDetails) {
                    return response()->json([
                        'status' => true,
                        'message' => "Message Details Created Successfully",
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
        $messageDetails = MessageDetail::where('id', $request['id'])->where('message_id', $request['message_id'])->where('from_id', $request['from_id'])->first();
        if (!isset($messageDetails))
        {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 200);
        }
        $messageDetails->delete();
        if ($messageDetails)
        {
            return response()->json([
                'status' => true,
                'message' => 'Message Details Deleted Successfully',
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
