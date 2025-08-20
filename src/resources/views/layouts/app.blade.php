<!-- 共通レイアウトのHTML -->
<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- 文字コードやレスポンシブ対応 -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- CSRFトークン -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ページタイトル -->
    <title>coachtechフリマ</title>

    <!-- 共通CSSの読み込み -->
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />

    <!-- 各ページごとの追加CSS -->
    @yield('css')
</head>

<body>
    {{-- ヘッダー --}}
    <header class="header">
        <div class="header__inner">
                <a class="header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                </a>
                <nav>
                    <ul class="header-nav">
                        {{-- 検索欄 --}}
                        <li class="header-nav__item">
                            <form action="/search" class="search-form" method="get">
                                <div class="search-item">
                                    <input class="search-item__input" type="text" name="keyword" placeholder="何をお探しですか？" value="{{ old('keyword') }}" />
                                </div>
                            </form>
                        </li>
                        {{-- ログイン状態に合わせてボタン切り替え --}}
                        <li class="header-nav__item">
                            {{-- ログアウトボタン --}}
                            @auth
                                <form class="form" action="/logout" method="post">
                                    @csrf
                                    <button class="logout_button">ログアウト</button>
                                </form>
                            @endauth

                            {{-- ログインボタン --}}
                            @guest
                                <a class="login_button" href="{{ route('login') }}">ログイン</a>
                            @endguest

                        </li>
                        {{-- マイページボタン --}}
                        <li class="header-nav__item">
                            <a class="mypage_link" href="/mypage">マイページ</a>
                        </li>
                        {{-- 出品ボタン --}}
                        <li class="header-nav__item">
                            <a href="/sell" class="exhibit_button">出品</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- ページごとの中身を表示 -->
    <main>
        @yield('content')

        @yield('scripts')

    </main>
</body>

</html>
