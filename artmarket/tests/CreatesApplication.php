<?php

namespace Tests;

use App;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

trait  CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();
        $this->clearConfigCache();
        App::setLocale('en');

        return $app;
    }

    private function clearConfigCache(): void
    {
        Artisan::call('config:clear');
    }
}
