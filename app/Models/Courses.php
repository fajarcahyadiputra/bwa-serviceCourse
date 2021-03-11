<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table = 'courses';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];
    protected $fillable = [
        'name', 'certifikate', 'thumnail',
        'type', 'status', 'price', 'description', 'mentor_id'
    ];

    public function mentors()
    {
        return $this->belongsTo('App\Models\Mentors', 'mentor_id');
    }
    public function chapters()
    {
        return $this->hasMany('App\Models\Chapters', 'course_id')->orderBy('id', 'ASC');
    }
    public function images()
    {
        return $this->hasMany('App\Models\ImageCourse', 'course_id')->orderBy('id', 'DESC');
    }
}
