<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\AuthenticateUser;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;



class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Fortifyにユーザー登録処理を任せる
        Fortify::createUsersUsing(CreateNewUser::class);

        //会員登録フォームの表示
        Fortify::registerView(function () {
            return view('auth.register');
        });

        //ログインフォームの表示
        Fortify::loginView(function () {
            return view('auth.login');
        });

        //ログイン試行制限：1分に10回まで
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });


    }
}
