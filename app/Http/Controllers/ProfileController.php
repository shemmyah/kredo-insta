<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatRoom;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function show($id) {
        $user = $this->user->findOrFail($id);

        // Initializing the unread count
        $unread_count = 0;

        // Check unread messages
        if(Auth::id() !== $user->id) {
            // Find the chat room between Auth user and the profile user
            $chat_room = ChatRoom::where(function($query) use ($id) {
                $query->where('user_one_id', Auth::id())->where('user_two_id', $id);
            })->orWhere(function($query) use ($id) {
                $query->where('user_one_id', $id)->where('user_two_id', Auth::id());
            })->first();

            if($chat_room) {
                // Count unread messages
                $unread_count = $chat_room->messages
                    ->where('is_read', false)
                    ->where('sender_id', $id)
                    ->count();
            }
        }
        return view('users.profile.show')
            ->with('unread_count', $unread_count)
            ->with('user', $user);
    }

    public function edit() {
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')->with('user', $user);  
    }

    public function update(Request $request) {
        $request->validate([
            'name'          => 'required|min:1|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar'        => 'mimes:jpg,jpeg,gif,png|max:1048',
            'introduction'  => 'max:100'
        ]);

        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        if($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        #Save
        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function likes($id) {
        $user = $this->user->findOrFail($id);

        $liked_posts = $user->likedPosts()->with('images')->get();

        return view('users.profile.likes')
                ->with('user', $user)
                ->with('liked_posts', $liked_posts);
    }

    public function followers($id) {
        $user = $this->user->findOrFail($id);

        $followers = $user->followers;

        return view('users.profile.followers')
                ->with('user', $user)
                ->with('followers', $followers);
    }

    public function following($id) {
        $user = $this->user->findOrFail($id);

        $following = $user->following;

        return view('users.profile.following')
                ->with('user', $user)
                ->with('following', $following);
    }
}
