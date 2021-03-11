<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyCouses extends Model
{
    protected $table = 'my_courses';
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:m:i',
        'created_at' => 'datetime:Y-m-d H:m:i'
    ];
    protected $fillable = [
        'course_id', 'user_id'
    ];

    public function courses()
    {
        return $this->belongsTo('App\Models\Courses', 'course_id');
    }
}
