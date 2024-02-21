<?php

namespace App\Providers;

use App\Exceptions\ResourceNotFound;
use App\Models\{
    Account,
    AccountType,
    PostingPeriod,
    Subsidiary,
    TransactionType,
    DocumentSeries,
    Book
};
use App\Models\StakeHolder;
use App\Models\StakeHolderGroup;
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

        Route::bind('accounts', function ($value) {
            return Account::find($value) ?? throw new ResourceNotFound('Account not found.', 404);
        });

        Route::bind('book', function ($value) {
            return Book::find($value) ?? throw new ResourceNotFound('Book not found.', 404);
        });


        Route::bind('type', function ($value) {
            return AccountType::find($value) ?? throw new ResourceNotFound('Category not found.', 404);
        });

        Route::bind('posting-period', function ($value) {
            return PostingPeriod::find($value) ?? throw new ResourceNotFound('Posting period not found.', 404);
        });

        Route::bind('transaction-type', function ($value) {
            return TransactionType::find($value) ?? throw new ResourceNotFound('Transaction type not found.', 404);
        });

        Route::bind('subsidiary', function ($value) {
            return Subsidiary::find($value) ?? throw new ResourceNotFound('Subsidiary not found.', 404);
        });


        Route::bind('document-series', function ($value) {
            return DocumentSeries::find($value) ?? throw new ResourceNotFound('Document series not found.', 404);
        });

        Route::bind('stakeholder-group', function ($value) {
            return StakeHolderGroup::find($value) ?? throw new ResourceNotFound('Stakeholder Group not found.', 404);
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
