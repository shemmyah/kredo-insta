<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post;
    private $user;
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_posts = $this->post->latest()->get();
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        return view('users.home')
                    // ->with('all_posts', $all_posts);
                    ->with('home_posts', $home_posts)
                    ->with('suggested_users', $suggested_users);
    }

    #Get the posts of the users that the AUTH user is following
    public function getHomePosts() {
        $all_posts = $this->post->latest()->get(); //get all posts
        $home_posts = []; //create an array for filtered home posts

        foreach($all_posts as $post){ //loop through all posts
            //add a condition
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                //posts from followed users OR post user id is equals to LOGGED IN user
                $home_posts[] = $post; //if conditions met, all data should be inside the array
            }
        }

        return $home_posts; // return array
    }

    #get the users that the AUTH user is not following
    public function getSuggestedUsers() {
        $all_users = $this->user->all()->except(Auth::user()->id);//get ALL users EXCEPT LOGGED IN user
        $suggested_users = []; //create an array for suggested users

        foreach($all_users as $user){//loop through all users
            //add a condition
            if(!$user->isFollowed()){
                //! - not, means not followed users
                $suggested_users[] = $user; // if conditions are met, put users in suggested users array
            }
        }

        return $suggested_users; //return the array
    }

    public function search(Request $request) {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
