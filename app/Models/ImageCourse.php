<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    protected $table = 'image_courses';
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:m:i',
        'created_at' => 'datetime:Y-m-d H:m:i'
    ];
    protected $fillable = [
        'course_id', 'image'
    ];
}
