<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;


class ItemController extends Controller
{
    /**
     * 一覧表示
     * - 「おすすめ」タブ: 全商品の中から自分の出品を除外して表示
     * - 「マイリスト」タブ: ログインユーザーのお気に入り商品を表示
     * - 未ログインでマイリストを見ようとした場合は空を返す
     */
    public function index(Request $request)
    {
        //現在表示しているタブ（デフォルトは "all"）
        $tab = $request->input('tab', 'all');

        if ($tab === 'mylist') {
            //未ログイン → 空のコレクション
            if (!Auth::check()) {
                $items = collect();
            } else {
                //ログイン済み → お気に入り商品を取得
                $items = Auth::user()
                    ->favoriteItems()
                    ->get();
            }
        } else {
            //おすすめ一覧
            $query = Item::latest();

            //ログイン済みの場合は、自分の出品を除外
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
        //商品に関連する情報をロード
        $item->load([
            'favoritedByUsers',
            'categories',
            'comments.user'
        ]);

        //ログイン中なら、そのユーザーのお気に入りもロード
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
     * - 「おすすめ」タブ: 全商品からキーワード検索
     * - 「マイリスト」タブ: お気に入り商品をキーワード検索
     * - 未ログインでマイリスト検索した場合は空を返す
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
     * 出品フォーム表示
     */
    public function create()
    {
        // カテゴリを全取得
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    /**
     * 商品出品処理
     */
    public function store(ExhibitionRequest $request)
    {
        $data = $request->only(['title', 'description', 'brand', 'condition', 'price']);

        //画像がある場合は storage/app/public/images に保存
        $data['img_url'] = $request->file('image')
            ? $request->file('image')->store('images', 'public')
            : 'dummy.jpg';

        //商品を作成し、カテゴリも紐付ける
        Item::createWithCategories($data, $request->category_ids ?? []);

        return redirect('/?tab=all');
    }

    /**
     * コメント保存処理
     */
    public function addComment(CommentRequest $request, Item $item)
    {
        //ログインしていない場合はエラーを返す
        if (!auth()->check()) {
            return redirect() ->back()
                ->withErrors([
                    'auth' => 'コメントを投稿するにはログインしてください。'
                ]);
        }

        //コメントを保存
        $item->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->route('items.show', $item);

    }

}
