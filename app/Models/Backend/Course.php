<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function science(){
        return $this->belongsTo(Science::class, 'science_id');
    }

    public function centers(){
        return $this->hasMany(TrainingCenter::class,'id', 'center_id')->with('teachers');
   }
}
