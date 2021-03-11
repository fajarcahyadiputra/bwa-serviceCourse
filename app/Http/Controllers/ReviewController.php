<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use App\Models\Courses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $rule = [
            'course_id' => 'integer|required',
            'user_id' => 'integer|required',
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $course = Courses::find($request->course_id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ]);
        }
        $user = getUser($request->user_id);
        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }
        $isExists = Reviews::where('course_id', $request->course_id)->where('user_id', $request->user_id)->exists();
        if ($isExists) {
            return response()->json([
                'status' => 'error',
                'message' => "review allready exist"
            ]);
        }
        $saveReview = Reviews::create($data);
        if ($saveReview) {
            return response()->json([
                'status' => 'success',
                'data'   => $saveReview
            ]);
        }
    }

    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $rule = [
            'rating' => 'integer|min:1|max:5',
            'note'   => 'string'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ]);
        }
        $review = Reviews::find($id);
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ]);
        }
        $review->fill($request->all());
        if ($review->update()) {
            return response()->json([
                'status' => 'success',
                'data'   => $review
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Reviews::find($id);
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review is not found'
            ]);
        }
        if ($review->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'review deleted'
            ]);
        }
    }
}
