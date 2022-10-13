<?php

namespace App\Providers;

use App\Services\UserService;
use Blade;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('ru');
        Blade::stringable(function (\Illuminate\Support\Carbon $dateTime) {
            return $dateTime->isoFormat('D MMMM YYYY');
        });


        $this->app->singleton(UserService::class, function () {
            return new UserService();
        });


        Blueprint::macro('userId', function ($column = 'user_id', $comment = 'User ID') {
            $blueprint = $this->unsignedBigInteger($column, false)->comment($comment);
            $this->foreign($column)
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            return $blueprint;
        });

        Blueprint::macro('orderId', function () {
            $blueprint = $this->unsignedBigInteger('order_id')->comment('Order ID');
            $this->foreign('order_id')
                ->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            return $blueprint;
        });

        Blueprint::macro('offerId', function () {
            $blueprint = $this->unsignedBigInteger('offer_id')->comment('Offer ID');
            $this->foreign('offer_id')
                ->references('id')->on('offers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            return $blueprint;
        });

        Blueprint::macro('updatedAt', function () {
            return $this->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        Blueprint::macro('createdAt', function () {
            return $this->timestamp('updated_at')->useCurrent();
        });

    }
}
