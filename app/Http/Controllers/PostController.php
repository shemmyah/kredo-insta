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

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {
        // 1. バリデーション（nullableにして、空欄を許容する）
        $request->validate([
            'category'    => 'required|array|max:3',
            'description' => 'required|max:1000',
            'image.*'     => 'nullable|mimes:jpeg,jpg,png,gif|max:1048',
        ]);

        // 2. Post本体の保存
        $this->post->user_id = auth()->id();
        $this->post->description = $request->description;
        $this->post->save();

        // 3. カテゴリ保存（既存の処理）
        // ...

        // 4. 画像の保存（空欄を飛ばして保存）
        if ($request->hasFile('image')) {
            // array_filterで、中身が空の入力欄を除外します
            foreach ($request->file('image') as $file) {
                if ($file) { // ファイルが存在する場合のみ処理
                    $image_name = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/images/', $image_name);

                    $this->post->images()->create([
                        'image_path' => $image_name
                    ]);
                }
            }
        }

        return redirect()->route('index');
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
            'image.*'     => 'nullable|mimes:jpeg,jpg,png,gif|max:1048' // 複数画像に対応
        ]);

        # 2. 投稿本体（Descriptionなど）の更新
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;
        $post->save();

        # 3. 画像の更新処理（ここが「3」の内容です）
        // もし新しい画像が一つでも選択されていたら
        if ($request->hasFile('image')) {

            // ① 古い画像のレコードを images テーブルから削除
            // (注意：サーバー内のファイルを消す処理は一旦省いて、DBの入れ替えを優先します)
            $post->images()->delete();

            // ② 新しい画像を1枚ずつ保存する（storeと同じループ処理）
            foreach ($request->file('image') as $file) {
                if ($file) {
                    $image_name = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/images/', $image_name);

                    // imagesテーブルに保存
                    $post->images()->create([
                        'image_path' => $image_name
                    ]);
                }
            }
        }

        # 4. カテゴリの更新（既存の処理）
        $post->categoryPost()->delete();
        $category_post = []; // 初期化
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        # 5. 詳細画面へリダイレクト
        return redirect()->route('post.show', $id);
    }

    public function destroy($id)
    {
        $this->post->destroy($id);
        return redirect()->route('index');
    }
}
