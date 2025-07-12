<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reg_no',
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'contact',
        'whatsapp_number',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the course enrollments for the user.
     */
    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get the courses that the user is enrolled in.
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->withPivot('status', 'payment_type', 'approved_at')
                    ->withTimestamps();
    }

    /**
     * Get the approved course enrollments for the user.
     */
    public function approvedEnrollments()
    {
        return $this->courseEnrollments()->where('status', 'approved');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Check if the user is an instructor.
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    /**
     * Generate a unique registration number.
     * Format: <last 2 digit of the year><student number 5 digits>
     * Example: 2400001 (2024, student 00001)
     */
    public static function generateRegNo()
    {
        $currentYear = date('Y');
        $lastTwoDigits = substr($currentYear, -2);
        
        // Find the last student number for this year
        $lastUser = self::where('reg_no', 'like', $lastTwoDigits . '%')
                        ->orderBy('reg_no', 'desc')
                        ->first();

        if ($lastUser) {
            $lastStudentNumber = (int) substr($lastUser->reg_no, -5);
            $newStudentNumber = $lastStudentNumber + 1;
        } else {
            $newStudentNumber = 1;
        }

        return $lastTwoDigits . str_pad($newStudentNumber, 5, '0', STR_PAD_LEFT);
    }
}
