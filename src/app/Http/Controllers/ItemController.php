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
    //一覧表示
    public function index()
    {
        $items = Item::all();//全件取得
        return view('index', compact('items'));
    }

    //検索処理
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::keywordSearch($keyword)->get();

        return view('index', compact('items'));
    }

    //商品出品
    public function create()
    {
        $categories = Category::all(); // カテゴリを全取得

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('images', 'public');

        $item = new Item();
        $item->title = $request->title;
        $item->description = $request->description;
        $item->img_url = $imagePath;
        $item->brand = $request->brand;
        $item->condition = $request->condition;
        $item->price = $request->price;
        $item->user_id = Auth::id();
        $item->status = 0;
        $item->save();

        //中間テーブルに保存
        $item->categories()->attach($request->category_ids);


        return redirect('/')->with('success', '商品を出品しました！');
    }
}
