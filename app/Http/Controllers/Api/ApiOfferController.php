<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Backend\Offer;

class ApiOfferController extends Controller
{
    public function offers(){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        $offers = Offer::select('offers.id','offers.title', 'offers.image')
        ->get();
        return $this->sendResponse($offers, true, "");
    }

    public function offerContent($offer_id){
        $developer = $this->developer();
        if ($developer == null) {
            return $this->sendResponse(null, false, "Not Found Developer");
        }
        $req_count = $developer->req_count;
        $req_count = $req_count + 1;
        $developer->update([
            'req_count' => $req_count
        ]);
        return $this->sendResponse(Offer::where('id', $offer_id)
        ->select('offers.id', 'offers.title', 'offers.content')
        ->first(), true, "");
    }
}
