<?php

namespace App\Http\Controllers;

use App\Models\ImageCourse;
use App\Models\Courses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ImageCourseController extends Controller
{
    public function index(Request $request)
    {
        $imageCourse = ImageCourse::query();
        $courseId    = $request->query('course_id');
        $imageCourse->when($courseId, function ($query) use ($courseId) {
            return $query->where('course_id', $courseId);
        });
        return response()->json([
            'status' => 'success',
            'data'   => $imageCourse->get()
        ]);
    }
    public function show($id)
    {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image is not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data'   => $imageCourse
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            'course_id' => 'required|integer',
            'image'  => 'required|url'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        if ($request->course_id) {
            $course = Courses::find($request->course_id);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course is not found'
                ], 404);
            }
        }
        $newImageCourse = ImageCourse::create($data);
        return response()->json([
            'status' => 'error',
            'message' => $newImageCourse
        ]);
    }
    public function update(Request $request, $id)
    {
        $rule = [
            'course_id' => 'integer',
            'image'  => 'url'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image ss not found'
            ], 404);
        }
        if ($request->course_id) {
            $course = Courses::find($request->course_id);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course is not found'
                ], 404);
            }
        }
        $imageCourse->fill($data);
        if ($imageCourse->save()) {
            return response()->json([
                'status' => 'success',
                'data' => $imageCourse
            ]);
        }
    }
    public function destroy($id)
    {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image s not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'iamage deleted'
        ]);
    }
}
