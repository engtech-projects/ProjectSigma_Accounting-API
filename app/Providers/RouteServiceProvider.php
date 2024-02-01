<?php

namespace App\Providers;

use App\Exceptions\ResourceNotFound;
use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\AccountType;
use App\Models\JournalBook;
use Exception;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Route::bind('accounts',function($value) {
            $account = Account::find($value) ?? throw new ResourceNotFound('Account not found.',404);
            return $account;
        });

        Route::bind('category',function($value) {
            $accountCategory = AccountCategory::find($value) ?? throw new ResourceNotFound('Category not found.',404);
            return $accountCategory;
        });

        Route::bind('type',function($value) {
            $accountType = AccountType::find($value) ?? throw new ResourceNotFound('Category not found.',404);
            return $accountType;
        });

        Route::bind('book',function($value) {
            $journalBook = JournalBook::find($value) ?? throw new ResourceNotFound('Journal Book not found.',404);
            return $journalBook;
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api/v1/')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
