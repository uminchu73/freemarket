<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;


class ItemController extends Controller
{
    /**
     * 一覧表示
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');

        $items = ($tab === 'mylist')
            ? (Auth::check() ? Auth::user()->mylist() : redirect()->route('login'))
            : Item::recommended(Auth::id());

        // redirect が返ってきたらそのまま返す
        if ($items instanceof \Illuminate\Http\RedirectResponse) {
            return $items;
        }


        return view('index', compact('items', 'tab'));
    }

    /**
     * 詳細表示
     */
    public function show(Item $item)
    {
        // 商品に紐づくお気に入りユーザーもロード
        $item->load('favoritedByUsers', 'categories','comments.user');

        // ログインユーザーならお気に入りリレーションもロード
        if (auth()->check()) {
            auth()->user()->load('favoriteItems');
        }

        // コメントもビューに渡す
        $comments = $item->comments;


        return view('detail', compact('item', 'comments'));
    }

    /**
     * 検索処理
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::keywordSearch($keyword)->get();

        return view('index', compact('items'))->with('tab', 'all');

    }

    /**
     * 商品出品処理
     */
    public function create()
    {
        // カテゴリを全取得
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $data = $request->only(['title', 'description', 'brand', 'condition', 'price']);
        $data['img_url'] = $request->file('image')->store('images', 'public');

        Item::createWithCategories($data, $request->category_ids ?? []);

        return redirect('/?tab=all')->with('success', '商品を出品しました！');
    }

    /**
     * コメント保存処理
     */
    public function addComment(CommentRequest $request, Item $item)
    {
        $item->comments()->create([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
        ]);

        return back();
    }

}
