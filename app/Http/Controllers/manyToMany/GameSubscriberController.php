<?php

namespace App\Http\Controllers\manyToMany;

use App\Game;
use App\manyToManyModels\GameSubscriber;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.games.myGamesSubscriptions', [
            'games' => Auth::user()->games
        ]);
    }

    public function gamesSubscriptionsById(int $id)
    {
        $user = User::findOrFail($id);
        $games = GameSubscriber::with('game')->where('user_id',$id)->get();
        return view('pages.games.myGamesSubscriptions', [
            'games' => $games,
            'user'=>$user
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|int|min:1'
        ]);
        try {
            GameSubscriber::create([
                'user_id' => Auth::user()->id,
                'game_id' => $request->game_id
            ]);
            return redirect()->back()->with('success', 'Successfully subscribed');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'It looks like you have been already subscribed to the game');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $gameShortAddress
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allGameSubscribers(string $gameShortAddress)
    {
        $game = Game::where('short_address',$gameShortAddress)->firstOrFail();
        return view('pages.games.gameSubscribers',[
            'subscribers'=>$game->subscribers,
            'game'=>$game
        ]);
    }

    public function onlineSubscribers(string $gameShortAddress)
    {
        $game = Game::where('short_address',$gameShortAddress)->firstOrFail();
        $gameSubscribers = $game->subscribers->filter(function ($item){
            if($item->user->isOnline()){
                return $item;
            }
        });
        return view('pages.games.gameSubscribers',[
            'subscribers'=>$gameSubscribers,
            'game'=>$game
        ]);
    }
    public function gameSubscribersFriends(string $gameShortAddress)
    {
        $game = Game::where('short_address',$gameShortAddress)->firstOrFail();
        $gameSubscribers = $game->subscribers->filter(function ($item){
            if($item->user->isFriend(Auth::user()->id)){
                return $item;
            }
        });
        return view('pages.games.gameSubscribers',[
            'subscribers'=>$gameSubscribers,
            'game'=>$game
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Game $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Game $game)
    {
        $gameSubscriber = GameSubscriber::where([
            ['user_id', Auth::user()->id],
            ['game_id', $game->id],
        ])->first();
        if ($gameSubscriber->delete()) {
            return redirect()->back()->with('success', 'Successfully unsubscribed!!!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


    public function addModerator(GameSubscriber $gameSubscriber){
        if($gameSubscriber->update(['is_moderator'=>1])){
            return redirect()->back()->with('success', 'New moderator added!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function removeModerator(GameSubscriber $gameSubscriber){
        if($gameSubscriber->update(['is_moderator'=>0])){
            return redirect()->back()->with('success', 'Removed moderator!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
