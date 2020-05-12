<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

trait UploadTrait
{
    public function uploadPhoto(Request $request,$object,$propertyName,$path,$width=300,$height=300){
        $request->validate([
            $propertyName => 'required|file|image|max:5000'
        ]);
        if($request->has($propertyName)){
            if(isset($object->{$propertyName})){
                if(file_exists($object->{$propertyName})){
                    unlink($object->{$propertyName});
                }
            }
            $object->{$propertyName} = 'storage/'.$request->{$propertyName}->store($path,'public');
            $object->save();
            $image = Image::make(public_path($object->{$propertyName}))->fit($width, $height);
            $image->save();
        }
    }
}
