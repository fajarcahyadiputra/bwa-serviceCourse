<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mentors;
use App\Models\Courses;
use App\Models\MyCouses;
use App\Models\Reviews;
use App\Models\Chapters;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $course = Courses::query();
        $limit = $request->query('limit') ? $request->query('limit') : 10;
        $q     = $request->query('q');
        $status  = $request->query('status');
        $course->when($q, function ($query) use ($q) {
            return $query->whereRaw('name LIKE "%' . strtolower($q) . '%"');
        });
        $course->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        });
        if ($course) {
            return response()->json([
                'status' => 'success',
                'data'   => $course->paginate($limit)
            ]);
        }
    }
    public function show($id)
    {
        $course = Courses::with('chapters.lessons')
            ->with('mentors')
            ->with('images')
            ->find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ]);
        }

        $reviews = Reviews::where('course_id', $id)->get()->toArray();
        if (count($reviews) != 0) {
            $user_ids = array_column($reviews, 'user_id');
            $users    = getUserById($user_ids);
            if ($users['status'] === 'error') {
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    //get in table user berdasarkan user id yang ada di riview
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['user'] = $users['data'][$userIndex];
                }
            }
        }
        $totalSatudent = MyCouses::where('course_id', $id)->count();
        //withcount ambil data di table relasi dan hitung jumblah datanya;
        $chapter   = Chapters::where('course_id', $id)->withCount('lessons')->get()->toArray();
        $totalVideos = array_sum(array_column($chapter, 'lessons_count'));

        $course['review'] = $reviews;
        $course['total_video'] = $totalVideos;
        $course['total_student'] = $totalSatudent;
        return response()->json([
            'status' => 'success',
            'data'   => $course
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'certifikate' => 'required|boolean',
            'thumnail' => 'string|url',
            'type' => 'required|string|in:free, premium',
            'status' => 'required|string|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:beginner, intermediate,advance',
            'mentor_id' => 'required|integer',
            'description' => 'string'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ]);
        }
        $mentor = Mentors::find($request->input('mentor_id'));
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor is not found'
            ], 404);
        }
        $courses = Courses::create($data);
        return response()->json([
            'status' => 'success',
            'data'   => $courses
        ]);
    }
    public function update(Request $request, $id)
    {
        $rule = [
            'name' => 'string',
            'certifikate' => 'boolean',
            'thumnail' => 'string|url',
            'type' => 'string|in:free, premium',
            'status' => 'string|in:draft, published',
            'price' => 'integer',
            'level' => 'in:beginner, intermediate, advance',
            'mentor_id' => 'integer',
            'description' => 'string'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $course = Courses::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ], 404);
        }
        if ($request->mentor_id) {
            $mentor = Mentors::find($request->mentor_id);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor is not found'
                ], 404);
            }
        }
        $course->fill($data);
        if ($course->save()) {
            return response()->json([
                'status' => 'success',
                'data'   => $course
            ]);
        }
    }
    public function destroy($id)
    {
        $course = Courses::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not found'
            ], 404);
        }
        if ($course->delete()) {
            return response()->json([
                'status' => 'success',
                'message'   => 'course deleted'
            ]);
        }
    }
}
