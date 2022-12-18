<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Science extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function courses(){
        return $this->hasMany(Course::class, 'science_id');
    }
}
