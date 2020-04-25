<?php

namespace App\Http\Controllers;

use App\ProfileComment;
use App\Traits\RecipientIdTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCommentController extends Controller
{
    use RecipientIdTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $recipient_id = $this->getRecipientId($request);
        $this->authorize('postToWall',[ProfileComment::class,User::find($recipient_id)]);
        $writer_id = Auth::user()->id;
        if(ProfileComment::create([
            'comment' => $request->comment,
            'recipient_id' => $recipient_id,
            'writer_id' => $writer_id,
        ])){
            return redirect()->back()->with('success','Successfully added!!!');
        }else{
            return redirect()->back()->with('error','Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProfileComment $comment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(ProfileComment $comment)
    {
        $this->authorize('delete',$comment);
        if($comment->delete()){
            return redirect()->back()->with('success','Successfully deleted!!!');
        }else{
            return redirect()->back()->with('error','Something went wrong');
        }
    }
}
