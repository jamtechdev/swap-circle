<?php

namespace App\View\Composers;

use App\Support\AuthBrand;
use Illuminate\View\View;

class AuthViewComposer
{
    public function compose(View $view): void
    {
        $variables = AuthBrand::variables();

        view()->share($variables);
        $view->with($variables);
    }
}
