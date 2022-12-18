<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function teachers(){
        return $this->hasMany(Teacher::class,'id', 'teacher_id');
   }
}
