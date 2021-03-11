<?php

namespace App\Http\Controllers;

use App\Models\MyCouses;
use App\Models\Courses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $myCourse = MyCouses::query()->with('courses');
        $course_id = $request->query('course_id');
        $myCourse->when($course_id, function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        });
        return response()->json([
            'status' => 'success',
            'data' => $myCourse->get()
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            "course_id" => "required|integer",
            "user_id" => "required|integer"
        ];
        $validate = Validator::make($request->all(), $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $course = Courses::find($request->input('course_id'));
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ], 404);
        }
        $user = getUser($request->input('user_id'));
        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }
        $isExist = MyCouses::where('course_id', $request->input('course_id'))
            ->where('user_id', $request->input('user_id'))->exists();

        if ($isExist) {
            return response()->json([
                'status' => 'success',
                'data'   => 'user allready taken this course'
            ], 409);
        }

        if ($course->type === 'premium') {
            if ($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'price can\'t be 0'
                ], 405);
            }
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);
            if ($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['status_code']);
            }
            return response()->json([
                'status' => 'success',
                'data'   => $order['data']
            ]);
        } else {
            $saveMyCourse = MyCouses::create($request->all());
            if ($saveMyCourse) {
                return response()->json([
                    'status' => 'success',
                    'data'   => $saveMyCourse
                ]);
            }
        }
    }
    public function makePremium(Request $request)
    {
        $data = $request->all();
        $newMycourse = MyCouses::create($data);
        return response()->json([
            'status' => 'success',
            'data'   => $newMycourse
        ]);
    }
}
