<?php


namespace App\Traits;


use Illuminate\Http\Request;

trait RecipientIdTrait
{
    public function getRecipientId(Request $request){
        preg_match('~id\/(\d+)~',$request->server('HTTP_REFERER'),$matches);
        return $matches[1];
    }
}
