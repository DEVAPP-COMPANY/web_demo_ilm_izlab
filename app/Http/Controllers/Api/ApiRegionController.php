<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Backend\Region;

class ApiRegionController extends Controller
{
    public function regions(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $regions = Region::select('id', 'name_uz', 'created_at')
        ->with('districts')->get();
        return $this->sendResponse($regions, true, "");
    }
}
