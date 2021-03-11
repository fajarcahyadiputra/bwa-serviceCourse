<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $table = 'reviews';
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:m:i',
        'created_at' => 'datetime:Y-m-d H:m:i'
    ];
    protected $fillable = [
        'user_id', 'course_id', 'rating', 'note'
    ];

    public function courses()
    {
        return $this->belongsTo('app\Models\Courses');
    }
}
