<?php

namespace Esign\Plytix\Tests;

use Esign\Plytix\PlytixServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::preventStrayRequests();
        MockClient::destroyGlobal();
    }

    protected function getPackageProviders($app): array
    {
        return [PlytixServiceProvider::class];
    }
}
