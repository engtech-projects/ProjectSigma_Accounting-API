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
use App\Models\AccountGroup;
use App\Models\StakeHolder;
use App\Models\StakeHolderGroup;
use App\Models\StakeHolderType;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


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

        Route::bind('type', function ($value) {
            return AccountType::findOrFail($value) ?? throw new HttpException(JsonResponse::HTTP_NOT_FOUND, 'Resource not found.');
        });

        Route::bind('stakeholder-group', function ($value) {
            return StakeHolderGroup::findOrFail($value) ?? throw new HttpException(JsonResponse::HTTP_NOT_FOUND, 'Resource not found.');
        });

        Route::bind('stakeholder-type', function ($value) {
            return StakeHolderType::findOrFail($value) ?? throw new HttpException(JsonResponse::HTTP_NOT_FOUND, 'Resource not found.');
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
