<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

trait UploadTrait
{
    public function uploadPhoto(Request $request,$object,$path){
        $request->validate([
            'photo' => 'required|file|image|max:5000'
        ]);
        if($request->has('photo')){
            if(isset($object->photo)){
                unlink($object->photo);
            }
            $object->photo = 'storage/'.$request->photo->store($path,'public');
            $object->save();
            $image = Image::make(public_path($object->photo))->fit(300, 300);
            $image->save();
        }
    }
}
