<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function centers(){
        return $this->hasOne(TrainingCenter::class,'id', 'center_id')->with('district');
    }
}
