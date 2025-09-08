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
        $tab = $request->input('tab', 'all');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $items = collect();//未ログインなら空
            } else {
                $items = Auth::user()
                    ->favoriteItems()
                    ->get();
            }
        } else {
            // おすすめ（全商品）ただし自分の出品は除外
            $query = Item::latest();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            $items = $query->get();
        }

        return view('index', compact('items', 'tab'));
    }


    /**
     * 詳細表示
     */
    public function show(Item $item)
    {
        // 商品に関連する情報をロード
        $item->load('favoritedByUsers', 'categories', 'comments.user');

        // ログインユーザーがいればお気に入りもロード
        if (auth()->check()) {
            auth()->user()->load('favoriteItems');
        }

        return view('detail', [
            'item' => $item,
            'comments' => $item->comments
        ]);
    }


    /**
     * 検索処理
     */
    public function search(Request $request)
    {
        $tab = $request->input('tab', 'all');
        $keyword = $request->input('keyword');

        if ($tab === 'mylist') {
            $items = Auth::check() 
                ? Auth::user()->searchFavoriteItems($keyword) 
                : collect();
        } else {
            $items = Item::keywordSearch($keyword)->latest()->get();
        }

        return view('index', compact('items', 'tab'));
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
        $data['img_url'] = $request->file('image')? $request->file('image')->store('images', 'public')
        : 'dummy.jpg';

        Item::createWithCategories($data, $request->category_ids ?? []);

        return redirect('/?tab=all')->with('success', '商品を出品しました！');
    }

    /**
     * コメント保存処理
     */
    public function addComment(CommentRequest $request, Item $item)
    {
        if (!auth()->check()) {
            return redirect() ->back()
            ->withErrors(['auth' => 'コメントを投稿するにはログインしてください。']);
        }

        $item->comments()->create([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
        ]);

        return redirect()->route('items.show', $item);

    }

}
