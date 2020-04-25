<?php

namespace App\Http\Controllers\oneHasMany;

use App\Game;
use App\oneHasManyModels\GamePosts;
use App\Post;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GamePostController extends Controller
{
    use UploadTrait;
    public function store(Request $request)
    {
        $request->validate([
           'title'=>'required',
           'post'=>'required',
        ]);
        $game = Game::findOrFail($request->game_id);
        $this->authorize('create',[GamePosts::class,$game]);
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
