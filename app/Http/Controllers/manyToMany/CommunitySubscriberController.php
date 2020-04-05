<?php

namespace App\Http\Controllers\manyToMany;

use App\Community;
use App\CommunitySubscriber;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunitySubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('pages.communities.myCommunitiesSubscriptions', [
            'communities' => Auth::user()->communities
        ]);
    }
    public function myCommunities(){
//        where(function($query) use ($user_id){
//            $query->where([
//                ['receiver_id',$user_id]
//            ])->orWhere([
//                ['sender_id',$user_id]
//            ]);
//        })->where('status',1)->get();

//        ['user_id',Auth::user()->id],
//            ['is_creator',1]

        $user = Auth::user();
        $communities = CommunitySubscriber::with('community')->where(function ($query) use ($user){
            $query->where([
                ['user_id',$user->id],
                ['is_creator',1]
            ])->orWhere([
                ['user_id',$user->id],
                ['is_admin',1]
            ])->orWhere([
                ['user_id',$user->id],
                ['is_moderator',1]
            ]);
        })->get();
        return view('pages.communities.myCommunitiesSubscriptions',[
            'communities'=>$communities
        ]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'community_id' => 'required|int|min:1'
        ]);
        try {
            CommunitySubscriber::create([
                'user_id' => Auth::user()->id,
                'community_id' => $request->community_id
            ]);
            return redirect()->back()->with('success', 'Successfully subscribed');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'It looks like you have been already subscribed to the community');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return \Illuminate\Http\Response
     */
    public function show(CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return \Illuminate\Http\Response
     */
    public function edit(CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Community $community
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Community $community)
    {
        $communitySubscriber = CommunitySubscriber::where([
            ['user_id', Auth::user()->id],
            ['community_id', $community->id],
        ])->first();
        if ($communitySubscriber->delete()) {
            return redirect()->back()->with('success', 'Successfully unsubscribed!!!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function allCommunitySubscribers(string $communityShortAddress)
    {
        $community = Community::where('short_address',$communityShortAddress)->firstOrFail();
        return view('pages.communities.communitySubscribers',[
            'subscribers'=>$community->subscribers,
            'community'=>$community
        ]);
    }
    public function communityOnlineSubscribers(string $communityShortAddress){
        $community = Community::where('short_address',$communityShortAddress)->firstOrFail();
        $communitySubscribers = $community->subscribers->filter(function ($item){
            if($item->user->isOnline()){
                return $item;
            }
        });
        return view('pages.communities.communitySubscribers',[
            'subscribers'=>$communitySubscribers,
            'community'=>$community
        ]);
    }
    public function communitySubscribersFriends(string $communityShortAddress){
        $community = Community::where('short_address',$communityShortAddress)->firstOrFail();
        $communitySubscribers = $community->subscribers->filter(function ($item){
            if($item->user->isFriend(Auth::user()->id)){
                return $item;
            }
        });
        return view('pages.communities.communitySubscribers',[
            'subscribers'=>$communitySubscribers,
            'community'=>$community
        ]);
    }

    public function addAdmin(CommunitySubscriber $communitySubscriber){
        $this->authorize('isCreator',[CommunitySubscriber::class,$communitySubscriber->community]);
        if($communitySubscriber->update([
            'is_admin'=>1,
            'is_moderator'=>1,
        ])){
            return redirect()->back()->with('success', 'New admin added!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function removeAdmin(CommunitySubscriber $communitySubscriber){
        $this->authorize('isCreator',[CommunitySubscriber::class,$communitySubscriber->community]);
        if($communitySubscriber->update([
            'is_admin'=>0,
            'is_moderator'=>0,
        ])){
            return redirect()->back()->with('success', 'Admin removed!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function addModerator(CommunitySubscriber $communitySubscriber){
        $this->authorize('isAdmin',[CommunitySubscriber::class,$communitySubscriber->community]);
        if($communitySubscriber->update(['is_moderator'=>1])){
            return redirect()->back()->with('success', 'New moderator added!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function removeModerator(CommunitySubscriber $communitySubscriber){
        $this->authorize('isAdmin',[CommunitySubscriber::class,$communitySubscriber->community]);
        if($communitySubscriber->update(['is_moderator'=>0])){
            return redirect()->back()->with('success', 'Moderator removed!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function communitiesSubscriptionsById(int $id){
        $user = User::findOrFail($id);
        $communities = CommunitySubscriber::with('community')->where('user_id',$user->id)->get();
        return view('pages.communities.myCommunitiesSubscriptions', [
            'communities' => $communities,
            'user'=>$user
        ]);
    }

}
