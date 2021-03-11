<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapters;
use App\Models\Lessons;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        $lessons = Lessons::query();
        $chapterId = $request->query('chapter_id');
        if ($chapterId) {
            $chapter = Chapters::find($chapterId);
            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'chapter is not found'
                ], 404);
            }
            $lessons->when($chapterId, function ($query) use ($chapterId) {
                return $query->where('chapter_id', $chapterId);
            });
        }
        return response()->json([
            'status' => 'success',
            'data'  => $lessons->get()
        ]);
    }
    public function show($id)
    {
        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson is not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            'name' => 'string|required',
            'video' => 'string|required',
            'chapter_id' => 'integer|required'
        ];
        $data = $request->all();
        $validate = Validator($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        //check chapter
        $chapter = Chapters::find($request->chapter_id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter is not found'
            ], 404);
        }
        $lesson = Lessons::create($data);
        if ($lesson) {
            return response()->json([
                'status' => 'success',
                'data' => $lesson
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $rule = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];
        $data = $request->all();
        $validate = Validator($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        //check lesson
        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson is not found'
            ], 404);
        }
        //check chapter
        if ($request->chapter_id) {
            $chapter = Chapters::find($request->chapter_id);
            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'chapter is not found'
                ], 404);
            }
        }
        $lesson->fill($data);
        if ($lesson->save()) {
            return response()->json([
                'status' => 'success',
                'data' => $lesson
            ]);
        }
    }
    public function destroy($id)
    {
        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson is not found'
            ], 404);
        }
        if ($lesson->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'lesson deleted'
            ]);
        }
    }
}
