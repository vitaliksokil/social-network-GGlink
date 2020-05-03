<?php

namespace App;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail, Htmlable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surname','nickname','photo','about','email','show_email','wall_can_edit','is_super_admin', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wall(){
        return $this->hasMany(ProfileComment::class,'recipient_id')->with('writer')->latest();
    }
    public function new_friends(){
        return $this->hasMany(FriendShip::class,'receiver_id')->select('sender_id')->where('status',0)->with('sender');
    }
    public function newMessages(){
        return DB::table('messages')
            ->select(DB::raw('count(`from`) as message_count,`from` as sender_id'))
            ->where([
            ['to',$this->id],
            ['is_read',false],])
            ->groupBy('from')->get();
    }
    public function requested_people(){
        return $this->hasMany(FriendShip::class,'sender_id')->select('receiver_id')->where('status',0)->with('receiver');
    }
    public function games(){
        return $this->hasMany(GameSubscriber::class,'user_id')->select('game_id')->with('game');
    }
    public function communities(){
        return $this->hasMany(CommunitySubscriber::class,'user_id')->select('community_id')->with('community');
    }
    public function friends() : Collection{
        $user_id = $this->id;
        $friendShips = FriendShip::where(function($query) use ($user_id){
            $query->where([
                ['receiver_id',$user_id]
            ])->orWhere([
                ['sender_id',$user_id]
            ]);
        })->where('status',1)->get();
        $friends = new Collection();
        foreach ($friendShips as $friendShip){
            if($friendShip->receiver_id == $user_id){
                $friends->push($friendShip->sender);
            }else{
                $friends->push($friendShip->receiver);
            }
        }
        return $friends;
    }
    public function mutualFriends():Collection{
        $user = $this;
        $user1Friends = Auth::user()->friends();
        $user2Friends = $user->friends();
        $mutualFriends = new Collection();
        foreach ($user1Friends as $user1Friend){
            $mutualFriend = $user2Friends->where('id',$user1Friend->id);
            if($mutualFriend->isNotEmpty()){
                $mutualFriends->push($mutualFriend);
            }
        }
        $mutualFriends = $mutualFriends->flatten();
        return $mutualFriends;
    }
    public function isFriend($id){
        $user_id = $this->id;
        $friend_id = $id;
        return FriendShip::where(function ($query) use($user_id,$friend_id){
            $query->where([
                ['receiver_id',$user_id],
                ['sender_id',$friend_id],
        ])->orWhere([
                ['receiver_id',$friend_id],
                ['sender_id',$user_id]
            ]);
        })->where('status',1)->first() ? true : false;
    }
    public function isOnline(){
        return Cache::has('user-is-online-' . $this->id);
    }
    public function onlineFriends(){
        return $this->friends()->filter(function ($user){
            if($user->isOnline()){
                return $user;
            }
        });
    }

    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        return $this->name . ' <span class="pink">"'.$this->nickname.'"</span> ' . $this->surname;
    }


    public function receivesBroadcastNotificationsOn()
    {
        return 'new.message.notification.'.$this->id;
    }
}
