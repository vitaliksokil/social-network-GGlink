<?php

namespace App\Http\Controllers;

use App\Community;
use App\CommunitySubscriber;
use App\Game;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommunityController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $communities = Community::all();
        return view('pages.communities.allCommunities',[
            'communities'=>$communities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $games = Game::all();
        return view('pages.communities.forms.create',[
            'games' =>$games
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required', 'min:2', 'string', 'max:255', 'unique:communities'],
            'short_address' => ['required', 'min:2', 'string', 'max:255', 'unique:communities'],
            'info' => [],
            'game_id' => ['required','min:1', 'integer'],
        ])->validate();
        $shortAddress = preg_replace('~ ~','',$request->short_address);
        $community = Community::create([
            'title' => $request->title,
            'short_address' => $shortAddress,
            'info' => $request->info,
            'game_id' => $request->game_id,
        ]);
        if ($community) {
            try {
                CommunitySubscriber::create([
                    'user_id' => Auth::user()->id,
                    'community_id' => $community->id,
                    'is_moderator' => 1,
                    'is_admin' => 1,
                    'is_creator' => 1,
                ]);
                if($request->logo){
                    $this->uploadPhoto($request, $community, 'logo', 'img/communities/logos');
                }
                if($request->poster) {
                    $this->uploadPhoto($request, $community, 'poster', 'img/games/posters', 1090, 300);
                }
            }catch (\Exception $exception){
                $community->delete();
                return redirect()->back()->with('error', 'Something went wrong');
            }
            return redirect()->back()->with('success', 'Successfully added');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $gameShortAddress
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $gameShortAddress)
    {
        $community = Community::where('short_address', '=', $gameShortAddress)->firstOrFail();
        $subscribers = $community->subscribers->shuffle();
        $subscribers = count($subscribers) < 5 ? $subscribers->random(count($subscribers)):$subscribers->random(5);
//        $posts = $game->posts->sortByDesc('created_at');
        return view('pages.communities.community', [
            'community' => $community,
            'subscribers'=>$subscribers,
//            'posts'=>$posts,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Community $community
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Community $community)
    {
        $this->authorize('isAdmin',[CommunitySubscriber::class,$community]);

        return view('pages.communities.forms.edit',[
            'community'=>$community,
            'games'=>Game::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Community $community
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Community $community)
    {
        $this->authorize('isAdmin',[CommunitySubscriber::class,$community]);

        Validator::make($request->all(), [
            'title' => ['required', 'min:2', 'string', 'max:255', Rule::unique('communities')->ignore($community->id)],
            'short_address' => ['required', 'min:2', 'string', 'max:255', Rule::unique('communities')->ignore($community->id)],
            'info' => [],
            'game_id' => ['required','min:1', 'integer'],
        ])->validate();
        $shortAddress = preg_replace('~ ~','',$request->short_address);
        if ($community->update([
            'title' => $request->title,
            'short_address' => $shortAddress,
            'info' => $request->info,
            'game_id' => $request->game_id,
        ])) {
            if($request->logo){
                $this->uploadPhoto($request, $community, 'logo', 'img/communities/logos');
            }
            if($request->poster) {
                $this->uploadPhoto($request, $community, 'poster', 'img/games/posters', 1090, 300);
            }
            return redirect()->back()->with('success', 'Successfully updated');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Community $community
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Community $community)
    {
        $this->authorize('isCreator',[CommunitySubscriber::class,$community]);
        if($community->delete()){
            return redirect()->back()->with('success', 'Successfully deleted');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
