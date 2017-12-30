<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;

abstract class BaseTestCase extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->refreshApplication();
        Artisan::call('migrate');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        Artisan::call('migrate:reset');
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function toArray()
    {
        return json_decode($this->response->getContent(), true);
    }
}
