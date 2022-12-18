<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    public function center(){
        return $this->belongsTo(TrainingCenter::class, 'center_id')->select('id', 'name', 'main_image');
    }

    public function user(){
        return $this->belongsTo(AppUser::class, 'user_id');
    }
}
