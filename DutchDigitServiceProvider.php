<?php

namespace JordanSalazar\DutchDigitServiceProvider;

use Illuminate\Support\ServiceProvider;
use App\Validators\DutchDigitValidator;

class DutchDigitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new DutchDigitValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     *
     * @return void
     */
    public function register()
    {
    }
}
