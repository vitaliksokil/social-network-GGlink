<?php

namespace App\Http\Controllers\manyToMany;

use App\Community;
use App\manyToManyModels\CommunitySubscriber;
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

        if($search = \Request::get('q')){
            $communities = Auth::user()->communities->filter(function($item) use ($search){
                if(stristr($item->community->title,$search)){
                    return  $item;
                }
            });
        }else{
            $communities = Auth::user()->communities;
        }
        return view('pages.communities.myCommunitiesSubscriptions', [
            'communities' => $communities
        ]);
    }
    public function myCommunities(){
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
        if($search = \Request::get('q')){
            $communities = $communities->filter(function ($item) use($search){
                if(stristr($item->community->title,$search)){
                    return $item;
                }
            });
        }
        return view('pages.communities.myCommunitiesSubscriptions',[
            'communities'=>$communities
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
        $subscribers = $community->subscribers;
        if($search = \Request::get('q')){
            $subscribers = $subscribers->filter(function($item) use($search){
                if(
                    stristr($item->user->id,$search)
                    ||
                    stristr($item->user->name,$search)
                    ||
                    stristr($item->user->nickname,$search)
                    ||
                    stristr($item->user->surname,$search)
                ){
                    return $item;
                }
            });
        }
        return view('pages.communities.communitySubscribers',[
            'subscribers'=> $subscribers,
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
        if($search = \Request::get('q')){
            $communitySubscribers = $communitySubscribers->filter(function($item) use($search){
                if(
                    stristr($item->user->id,$search)
                    ||
                    stristr($item->user->name,$search)
                    ||
                    stristr($item->user->nickname,$search)
                    ||
                    stristr($item->user->surname,$search)
                ){
                    return $item;
                }
            });
        }
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
        if($search = \Request::get('q')){
            $communitySubscribers = $communitySubscribers->filter(function($item) use($search){
                if(
                    stristr($item->user->id,$search)
                    ||
                    stristr($item->user->name,$search)
                    ||
                    stristr($item->user->nickname,$search)
                    ||
                    stristr($item->user->surname,$search)
                ){
                    return $item;
                }
            });
        }
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
        $this->authorize('update',[CommunitySubscriber::class,$communitySubscriber]);
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
        $this->authorize('update',[CommunitySubscriber::class,$communitySubscriber]);
        if($communitySubscriber->update(['is_moderator'=>0])){
            return redirect()->back()->with('success', 'Moderator removed!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function communitiesSubscriptionsById(int $id){
        $user = User::findOrFail($id);
        $communities = CommunitySubscriber::with('community')->where('user_id',$user->id)->get();
        if($search = \Request::get('q')){
            $communities = $communities->filter(function($item) use($search){
               if(stristr($item->community->title,$search)){
                   return $item;
               }
            });
        }
        return view('pages.communities.myCommunitiesSubscriptions', [
            'communities' => $communities,
            'user'=>$user
        ]);
    }

}
