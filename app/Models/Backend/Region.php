<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function districts(){
        return $this->hasMany(District::class, 'region_id')->select('id', 'region_id', 'name_uz', 'created_at');
    }
}
