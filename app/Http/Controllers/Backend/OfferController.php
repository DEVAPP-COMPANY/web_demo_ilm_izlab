<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Backend\Offer;

class OfferController extends Controller
{
    public function offerView(){
        $offers = Offer::all();
        return view('backend.offer.offer_view', compact('offers'));
    }

    public function offerAdd(){
        return view('backend.offer.offer_add');
    }

    public function offerStore(Request $request){
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'image.required' => 'Upload Image',
            'content.required' => 'Input Content',
        ]);
        // dd($request);
        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'upload/offer/image/';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path.$image_full_name;
        $success = $image->move($upload_path,$image_full_name);

        Offer::insert([
            'title' => $request->title,
            'image' => $save_url_image,
            'content' => $request->content,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Offer Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.offer')->with($notification);
    }

    public function offerEdit($id){
        $offer = Offer::findOrFail($id);
        return view('backend.offer.offer_edit', compact('offer'));
    }

    public function offerUpdate(Request $request, $id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ],
        [
            'title.required' => 'Input Title',
            'content.required' => 'Input Content',
        ]);

        $old_image = $request->old_image;

        if ($request->file('image')) {
            unlink($old_image);
            $image = $request->file('image');

            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'upload/offer/image/';    //Creating Sub directory in Public folder to put image
            $save_url_image = $upload_path.$image_full_name;
            $success = $image->move($upload_path,$image_full_name);

            Offer::findOrFail($id)->update([
                'title' => $request->title,
                'image' => $save_url_image,
                'content' => $request->content,
            ]);
    
            $notification = array(
                'message' => 'Offer Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.offer')->with($notification);
            
        }
        Offer::findOrFail($id)->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $notification = array(
            'message' => 'Offer Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.offer')->with($notification);
    }

    public function offerDelete($id){
        $offer = Offer::findOrFail($id);
        $old_image = $offer->image;

        unlink($old_image);

        Offer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Offer Deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }
}
