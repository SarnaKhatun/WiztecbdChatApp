<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\HttpFoundation\isMethod;

class GroupDetailsController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
//            return $request->all();
            $user = Auth::user();
            if ($user) {

                    $validator = Validator::make($request->all(), [
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 400);
                    }
                    $requestData = $request->all();
                    $requestData['user_id'] = Auth::user()->id;
                    $groupDetailsData = GroupDetail::create($requestData);
                    if ($groupDetailsData) {
                        return response()->json([
                            'status' => true,
                            'message' => "Group Details Created Successfully",
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
        $groupDetails = GroupDetail::where('id', $request['id'])->where('user_id', Auth::user()->id)->first();
        if (!isset($groupDetails))
        {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 200);
        }
        $groupDetails->delete();
        if ($groupDetails)
        {
            return response()->json([
                'status' => true,
                'message' => 'Group Details Deleted Successfully',
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
