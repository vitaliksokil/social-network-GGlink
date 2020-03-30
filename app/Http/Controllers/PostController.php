<?php

namespace App\Http\Controllers;

use App\Post;
use App\Traits\RecipientIdTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
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
        $this->authorize('postToWall',[Post::class,User::find($recipient_id)]);


        $writer_id = Auth::user()->id;
        if(Post::create([
            'post' => $request->post,
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
     * @param \App\Post $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete',$post);
        if($post->delete()){
            return redirect()->back()->with('success','Successfully deleted!!!');
        }else{
            return redirect()->back()->with('error','Something went wrong');
        }
    }
}
