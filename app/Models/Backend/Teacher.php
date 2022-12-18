<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function checked(){
        return $this->hasOne(CourseTeacher::class, 'teacher_id');
    }
}
