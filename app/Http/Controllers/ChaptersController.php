<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courses;
use App\Models\Chapters;
use Illuminate\Support\Facades\Validator;

class ChaptersController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapters::query();
        $course_id = $request->query('course_id');
        $chapters->when($course_id, function ($query) use ($course_id) {
            return $query->where('course_id', $course_id);
        });
        return response()->json([
            'status' => 'success',
            'data'  => $chapters->get()
        ]);
    }
    public function show($id)
    {
        $chapter = Chapters::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            "name" => 'string|required',
            "course_id" => "integer|required"
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
            ], 404);
        }
        $newChapter = Chapters::create($data);
        if ($newChapter) {
            return response()->json([
                'status' => 'success',
                'data' => $newChapter
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $rule = [
            "name" => 'string',
            "course_id" => "integer"
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $chapter = Chapters::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter is not found'
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
        $chapter->fill($data);
        if ($chapter->save()) {
            return response()->json([
                'status' => 'success',
                'data'   => $chapter
            ]);
        }
    }
    public function destroy($id)
    {
        $chapter = Chapters::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ], 404);
        }
        if ($chapter->delete()) {
            return response()->json([
                'status' => 'success',
                'message'   => 'chapter deleted'
            ]);
        }
    }
}
