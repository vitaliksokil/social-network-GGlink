<?php

namespace App\Http\Controllers;

use App\Game;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GameController extends Controller
{
    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $games = Game::all();
        $games = $games->sortByDesc(function ($item){
            return count($item->subscribers);
        });
        return view('pages.games.gamesAll',[
            'games'=>$games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('pages.games.forms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required', 'min:2', 'string', 'max:255', 'unique:games'],
            'short_address' => ['required', 'min:2', 'string', 'max:255', 'unique:games'],
            'info' => ['required', 'min:10', 'string']
        ])->validate();
        $shortAddress = preg_replace('~ ~','',$request->short_address);
        $game = Game::create([
            'title' => $request->title,
            'info' => $request->info,
            'short_address' => $shortAddress,
        ]);
        if ($game) {
            $this->uploadPhoto($request, $game, 'logo', 'img/games/logos');
            $this->uploadPhoto($request, $game, 'poster', 'img/games/posters', 1090, 300);
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
        $game = Game::where('short_address', '=', $gameShortAddress)->firstOrFail();
        $subscribers = $game->subscribers->shuffle();
        $subscribers = count($subscribers) < 5 ? $subscribers->random(count($subscribers)):$subscribers->random(5);
        $posts = $game->posts->sortByDesc('created_at');
        return view('pages.games.game', [
            'game' => $game,
            'subscribers'=>$subscribers,
            'posts'=>$posts,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return response()->view('pages.games.forms.edit', ['game' => $game]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Game $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Game $game)
    {
        $this->authorize('update', $game);

        Validator::make($request->all(), [
            'title' => ['required', 'min:2', 'string', 'max:255', Rule::unique('games')->ignore($game->id)],
            'short_address' => ['required', 'min:2', 'string', 'max:255', Rule::unique('games')->ignore($game->id)],
            'info' => ['required', 'min:10', 'string']
        ])->validate();
        if ($request->poster) {
            // deleting poster and upload new one
            $this->uploadPhoto($request, $game, 'poster', 'img/games/posters', 1090, 300);
        }
        if ($request->logo) {
            $this->uploadPhoto($request, $game, 'logo', 'img/games/logos');
        }
        $shortAddress = preg_replace('~ ~','',$request->short_address);
        if ($game->update([
            'title' => $request->title,
            'short_address' => $shortAddress,
            'info' => $request->info,
        ])) {
            return redirect()->back()->with('success', 'Successfully updated');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);
        if (isset($game->logo)) {
            if (file_exists($game->logo)) {
                unlink($game->logo);
            }
        }
        if (isset($game->poster)) {
            if (file_exists($game->poster)) {
                unlink($game->poster);
            }
        }
        if ($game->delete()) {
            return redirect()->back()->with('success', 'Successfully deleted!!!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}
