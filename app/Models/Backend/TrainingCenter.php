<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    use HasFactory;
    // protected $casts = [
    //     'latitude' => 'float',
    //     'longitude' => 'float',
    // ];
    protected $guarded = [];

   public function district(){
      return $this->belongsTo(District::class, 'district_id')->select('id', 'region_id', 'name_uz as district_name');
   }

   public function region(){
      return $this->belongsTo(Region::class, 'region_id')->select('id', 'name_uz as region_name');
   }

   public function reviews(){
        return $this->hasMany(Review::class,'center_id');
   }

   public function courses(){
      return $this->hasMany(Course::class,'center_id')->with('science');
   }

   public function images(){
      return $this->hasMany(CenterImage::class,'center_id');
   }

   public function subsriptions(){
      return $this->hasMany(Subscription::class,'center_id');
   }

   public function teachers(){
      return $this->hasMany(Teacher::class,'center_id', 'id');
   }
}
