<?php

namespace App\Providers;

use App\Helpers\Navigation\CategoryItem;
use App\Helpers\Navigation\Navigation;
use App\Models\Glossary\Category;
use App\View\Components\Display\DataList;
use App\View\Components\Form\Text;
use Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        view()->composer('layouts.header', function ($view) {
            $navigation = new Navigation();

            $categories = Category::all();

            foreach ($categories as $category) {
                $navigation->addItem(CategoryItem::fromModel($category));
            }

            $view->with('navigation', $navigation->getItems());
        });

        Blade::component(DataList::class, 'display.list');
    }
}
