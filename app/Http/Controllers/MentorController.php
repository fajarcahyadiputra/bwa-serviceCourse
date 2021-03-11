<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mentors;

class MentorController extends Controller
{
    public function index()
    {
        $mentor = Mentors::all();
        if ($mentor) {
            return response()->json([
                'status' => 'success',
                'data'   => $mentor
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'somthing wrong'
            ], 404);
        }
    }
    public function show($id)
    {
        $mentor = Mentors::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor is not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data'   => $mentor
        ]);
    }
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'email' => 'required|email',
            'profession' => 'required|string',
            'profile' => 'required|url'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $mentor = Mentors::create($data);
        if ($mentor) {
            return response()->json([
                'status' => 'success',
                'data'   => $mentor
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'   => 'fail to create mentor'
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $rule = [
            'name' => 'string',
            'email' => 'email',
            'profile' => 'string',
            'profession' => 'string'
        ];
        $data = $request->all();
        $validate = Validator::make($data, $rule);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }
        $mentor = Mentors::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor is not found'
            ], 404);
        }
        if ($mentor->update($data)) {
            return response()->json([
                'status' => 'success',
                'data'   => $mentor
            ]);
        }
    }
    public function destroy($id)
    {
        $mentor = Mentors::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor is not found'
            ], 404);
        }
        if ($mentor->delete()) {
            return response()->json([
                'status' => 'success',
                'message'   =>  'mentor deleted'
            ]);
        }
    }
}
