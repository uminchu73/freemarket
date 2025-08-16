<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    /**
     * 一覧表示
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');

        $items = Item::forTab($tab)->paginate(10);

        return view('index', compact('items', 'tab'));
    }

    /**
     * 詳細表示
     */
    public function show(Item $item)
    {
        // 商品に紐づくお気に入りユーザーもロード
        $item->load('favoritedByUsers', 'categories');

        // ログインユーザーならお気に入りリレーションもロード
        if (auth()->check()) {
            auth()->user()->load('favoriteItems');
        }

        return view('detail', compact('item'));
    }

    /**
     * 検索処理
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::keywordSearch($keyword)->get();

        return view('index', compact('items'));
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
        $imagePath = $request->file('image')->store('images', 'public');

        $data = $request->only(['title', 'description', 'brand', 'condition', 'price']);
        $data['img_url'] = $imagePath;

        Item::createWithCategories($data, $request->category_ids);

        return redirect('/')->with('success', '商品を出品しました！');
    }
}
