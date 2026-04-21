<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment) {
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id) {
        $request->validate(
            [
            'comment_body' . $post_id => 'required|max:150'
        ],
        [
            'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
            'comment_body' . $post_id . '.max'      => 'The comment must not have more than 150 characters.'
        ]
        );

        $this->comment->body = $request->input('comment_body' . $post_id);
        //input() - gets the value from a form using "name" attribute
        $this->comment->user_id = Auth::user()->id;
        $this->comment->post_id = $post_id;
        $this->comment->save();

        return redirect()->route('post.show', $post_id);
    }

    // Aimi rewrote
    public function update(Request $request, $id)
{
    $request->validate([
        'comment_body' => 'required|max:150', 
    ]);

    $comment = Comment::findOrFail($id);

    if ($comment->user_id !== auth()->id()) {
        return redirect()->back(); // 本人じゃなければ戻す
    }

    $comment->body = $request->comment_body;
    $comment->save();

    return redirect()->back(); 
}

    public function destroy($id) {
       $this->comment->destroy($id);
        return redirect()->route('index');
    }
}
