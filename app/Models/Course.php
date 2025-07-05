<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'course_code',
        'instructor',
        'image',
        'is_free',
        'is_instructor_led',
        'description',
        'duration',
        'level',
        'syllabus_pdf',
    ];

    /**
     * Get the topics for the course.
     */
    public function topics()
    {
        return $this->hasMany(CourseTopic::class)->orderBy('order');
    }
}
