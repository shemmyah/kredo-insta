<?php
// バリデーション追加

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Models\Image;

class PostController extends Controller
{
    private $post;
    private $category;
    private $image;

    public function __construct(Post $post, Category $category, Image $image)
    {
        $this->post = $post;
        $this->category = $category;
        $this->image = $image;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {
        // 1. 投稿本体の保存
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->description = $request->description;
        $post->save();

        if ($request->hasFile('image')) {
            
            foreach ($request->file('image') as $file) {
                if ($file) {
                    $image_name = time() . '_' . $file->getClientOriginalName();

                    // storage ではなく、直接 public/images フォルダに保存する
                    $file->move(public_path('images'), $image_name);

                    $this->image->create([
                        'post_id'    => $post->id,
                        'image_path' => $image_name
                    ]);
                }
            }
        }
        return redirect()->route('post.show', $post->id);
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        #If the AUTH user is NOT the owner of the post, redirect to homepage
        if (Auth::user()->id != $post->user->id) {
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        #Get all the category IDs of this post. Save in an array
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
{
    # 1. バリデーション
    $request->validate([
        'category'    => 'required|array|between:1,3',
        'description' => 'required|min:1|max:1000',
        'image.*'     => 'nullable|mimes:jpeg,jpg,png,gif|max:1048'
    ]);

    # 2. 投稿本体の更新
    $post = $this->post->findOrFail($id);
    $post->description = $request->description;
    $post->save();

    # 3. カテゴリーの更新（リレーションの更新も必要であればここに追加してください）
    $post->categoryPost()->delete();
    foreach ($request->category as $category_id) {
        $post->categoryPost()->create(['category_id' => $category_id]);
    }

    # 4. 画像の更新処理
    if ($request->hasFile('image')) {
        // ① 古い画像のレコードを images テーブルから削除
        $post->images()->delete();

        // ② 新しい画像を1枚ずつ「直接 public/images」に保存
        foreach ($request->file('image') as $file) {
            if ($file) {
                // ファイル名を生成（storeと同じ形式）
                $image_name = time() . '_' . $file->getClientOriginalName();

                // storageではなく、直接 public/images フォルダに移動
                $file->move(public_path('images'), $image_name);

                // imagesテーブルに保存
                $post->images()->create([
                    'image_path' => $image_name
                ]);
            }
        }
    }

    return redirect()->route('post.show', $id);
}

    public function destroy($id)
    {
        $this->post->destroy($id);
        return redirect()->route('index');
    }
}
