<?php

namespace Orchestra\Installation\Tests\Feature;

use Orchestra\Testing\TestCase as Testing;

abstract class TestCase extends Testing
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Installation\InstallerServiceProvider::class,
        ];
    }
}
