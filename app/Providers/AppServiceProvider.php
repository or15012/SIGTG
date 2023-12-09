<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('weeks_between', function ($attribute, $value, $parameters, $validator) {
            $startDate = strtotime($validator->getData()['date_start']);
            $endDate = strtotime($value);

            $diffInWeeks = ceil(($endDate - $startDate) / 60 / 60 / 24 / 7);
            $diffInWeeks = intval($diffInWeeks);
            return $diffInWeeks === 16;
        });

        Validator::replacer('weeks_between', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':weeks', 16, $message);
        });
    }
}
