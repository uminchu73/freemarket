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
    <!-- ヘッダー -->
    <header class="header">
        <div class="header__inner">
                <a class="header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH">

                </a>
                <nav>
                    <ul class="header-nav">
                        @if (Auth::check())
                        <li class="header-nav__item">
                            <form class="form" action="/logout" method="post">
                                @csrf
                                <button class="logout_btn">ログアウト</button>
                            </form>
                        </li>
                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/mypage">マイページ</a>
                        </li>
                        <li class="header-nav__item">
                        <a href="/items/create" class="exhibit_btn">出品</a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- ページごとの中身を表示 -->
    <main>
        @yield('content')

    </main>
</body>

</html>
