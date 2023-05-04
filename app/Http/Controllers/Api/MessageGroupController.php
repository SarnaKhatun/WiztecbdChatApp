<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\GroupDetail;
use App\Models\Message;
use App\Models\MessageDetail;
use App\Models\MessageGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Group;

class MessageGroupController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
//            return $request->all();
            $user = Auth::user();
            $access_label = $user->role;
            if ($user) {
                if ($access_label == 1) {
                    $validator = Validator::make($request->all(), [
                        'group_name' => 'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 400);
                    }
                    $requestData = $request->all();
                    $requestData['user_id'] = Auth::user()->id;
                    $groupData = MessageGroup::create($requestData);
                    if ($groupData) {
                        return response()->json([
                            'status' => true,
                            'message' => "Message Group Created Successfully",
                        ], 200);

                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Something Is Wrong To Create Form",
                        ], 200);

                    }
                }
                else{
                    return response()->json([
                        'status' => false,
                        'message' => "Your are not an admin",
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid Token",
                ], 200);
            }
        }
    }


    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            $groupMessage = MessageGroup::where('id', $request['id'])->where('user_id', Auth::user()->id)->first();
            if (!isset($groupMessage)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message Group not found',
                ], 200);
            }

            $groupMessage->group_name = $request['group_name'];
            $groupMessage->save();
            if ($groupMessage) {
                return response()->json([
                    'status' => true,
                    'message' => 'Message Group updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Something Is Worng To Update",
                ], 200);
            }
        }
    }


    public function delete(Request $request)
    {
        $group = MessageGroup::where('id', $request['id'])->where('user_id', Auth::user()->id)->first();
        if (!isset($group)) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 200);
        }
        $group->delete();
        if ($group) {
            return response()->json([
                'status' => true,
                'message' => "Group Deleted Successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Something Is Wrong To Delete",
            ], 200);
        }
    }


    public function groupList(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->role = 1) {
                $groupList = MessageGroup::where('user_id', Auth::user()->id)->select('id', 'group_name')->latest()->paginate();
                if (count($groupList) > 0) {
                    return response()->json([
                        'status' => true,
                        'lists' => $groupList,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Group Lists Not Found",
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not an admin',
                ], 200);
            }
        }

    }


    public function groupWiseMessage(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $list = MessageGroup::where('user_id', $user->id)->with('groupDetail','messages')->select('id',
                'group_name',
                'user_id')->orderBy('id', 'DESC')->paginate(15);
            if (count($list) > 0) {
                return response()->json([
                    'status' => true,
                    'lists' => $list,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => " Data Lists Not Found",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Invalid Token",
            ], 200);
        }
    }


    public function group(Request $request)
    {
        $search = $request['search'];
        $data = MessageDetail::where('messages_id', 'LIKE', '%' . $search . '%')
//            ->with(['group' => function($query) use ($search) {
//                return $query->orWhere('user_id', 'LIKE', '%'. $search.'%')->orWhere('group_name', 'LIKE', '%'. $search.'%');
//            }])
            ->with(['messages' => function ($query) use ($search) {
                return $query->orWhere('sender_id', 'LIKE', '%' . $search . '%');
            }])->select('id', 'messages_id',
                'sender_id',
                'receiver_id',
                'message_body',
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


    public function GroupWiseMessageList(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $list = MessageGroup::where('user_id', $user->id)->with('groupDetail','messages')->select('id',
                'group_name',
                'user_id')->orderBy('id', 'DESC')->paginate(15);
            if (count($list) > 0) {
                return response()->json([
                    'status' => true,
                    'lists' => $list,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => " Data Lists Not Found",
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Invalid Token",
            ], 200);
        }
    }

}
