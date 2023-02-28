<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupDetail;
use App\Models\Message;
use App\Models\MessageGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessageListController extends Controller
{

    public function adminMessageList(Request $request)
    {
        $user = Auth::user();
        if ($user)
        {
            if ($user->role != 1)
            {
                return response()->json([
                    'status' => false,
                    'message'=> 'You are not an Admin'
                ], 200);
            }

            $search = $request['search'];
            $data = Message::where('group_id', 'LIKE', '%' . $search . '%')
                ->where('group_name', 'LIKE', '%' . $search . '%')
                ->with(['groupDetails' => function($query) use ($search) {
                    return $query->orWhere('user_id', 'LIKE', '%'. $search.'%');
                }])
                ->with(['messageDetails' => function($query) use ($search) {
                    return $query->orWhere('messages_id', 'LIKE', '%'. $search.'%');
                }])
                ->select('id', 'group_id',
                    'group_name',
                    'sender_id',
                    'receiver_id',
                    'name',
                    'image',
                    'status')
                ->paginate();

            if ($data) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data found successfully',
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found',
                    'data' => $data,
                ]);
            }
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Token',
            ], 200);
        }
    }


    public function clientStaffMessageList(Request $request)
    {
        $user = Auth::user();
        if ($user)
        {
            if ($user->role != 1){
                $groupMessageList = Message::where('user_id', Auth::user()->id)->where('group_id', $request['group_id'])
                    ->with('groupDetails','messageDetails')
                    ->select('id', 'group_id')->orderBy('id', 'ASC')
                    ->paginate();

                if (count($groupMessageList) > 0) {
                    return response()->json([
                        'status' => true,
                        'lists' => $groupMessageList,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Group Message Lists Not Found",
                    ], 200);
                }
            }
            else {
                return response()->json([
                    'status' => false,
                    'message' => "Not permitted",
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
