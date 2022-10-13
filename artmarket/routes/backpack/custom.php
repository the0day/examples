<?php

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    //Route::crud('user-profile', 'UserProfileCrudController');
    Route::crud('user-notification', 'UserNotificationCrudController');
    Route::crud('user-authorization', 'UserAuthorizationCrudController');
    Route::crud('glossary/country', 'Glossary\CountryCrudController');
    Route::crud('glossary/city', 'Glossary\CityCrudController');
    Route::crud('glossary/state', 'Glossary\StateCrudController');
    Route::crud('glossary/language', 'Glossary\LanguageCrudController');
    Route::crud('glossary/option', 'Glossary\OptionCrudController');
    Route::crud('glossary/option-group', 'Glossary\OptionGroupCrudController');
    Route::crud('glossary/offer-type', 'Glossary\OfferTypeCrudController');
    Route::crud('glossary/category', 'Glossary\CategoryCrudController');
    Route::crud('glossary/offer-purpose', 'Glossary\OfferPurposeCrudController');
    Route::crud('glossary/tag', 'Glossary\TagCrudController');
    Route::crud('offer', 'OfferCrudController');
    Route::crud('offer-option', 'OfferOptionCrudController');
    Route::crud('upload', 'UploadCrudController');
    Route::crud('media', 'MediaCrudController');
    Route::crud('order', 'OrderCrudController');
    Route::crud('settings', 'SettingsCrudController');
    Route::crud('payment', 'PaymentCrudController');
    Route::crud('payment-method', 'PaymentMethodCrudController');
});