<?php

namespace App\Http\Controllers\oneHasMany;

use App\Community;
use App\oneHasManyModels\CommunityPosts;
use App\Post;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommunityPostController extends Controller
{
    use UploadTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'post'=>'required',
        ]);
        $community = Community::findOrFail($request->game_id);
        $this->authorize('create',[CommunityPosts::class,$community]);
        try {
            $post = Post::create([
                'title' => $request->title,
                'post' => $request->post
            ]);
            if($request->photo){
                $this->uploadPhoto($request,$post,'photo','img/posts',640,360);
            }
            CommunityPosts::create([
                'community_id'=>$community->id,
                'post_id'=>$post->id,
            ]);
            return redirect()->back()->with('success','Successfully added');
        }catch (\Exception $e){
            if($post){
                $post->delete();
            }
            return redirect()->back()->with('error','Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     * @param Community $community
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Post $post,Community $community)
    {
        $this->authorize('delete',[$post,$community]);

        if ($post->photo){
            if(file_exists($post->photo)){
                unlink($post->photo);
            }
        }
        if($post->delete()){
            return redirect()->back()->with('success','Successfully deleted!');
        }
        return redirect()->back()->with('error','Something went wrong');
    }
}
