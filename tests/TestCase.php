<?php

namespace Esign\Plytix\Tests;

use Esign\Plytix\PlytixServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [PlytixServiceProvider::class];
    }
} 