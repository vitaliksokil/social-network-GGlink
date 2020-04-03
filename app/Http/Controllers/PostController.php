<?php

namespace App\Http\Controllers;

use App\Game;
use App\oneHasManyModels\GamePosts;
use App\Post;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $game = Game::findOrFail($request->game_id);
        $this->authorize('create',[Post::class,$game]);
        try {
            $post = Post::create([
                'title' => $request->title,
                'post' => $request->post
            ]);
            if($request->photo){
                $this->uploadPhoto($request,$post,'photo','img/posts',640,360);
            }
            GamePosts::create([
                'game_id'=>$game->id,
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
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     * @param Game $game
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Post $post,Game $game)
    {
        $this->authorize('delete',[$post,$game]);

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
