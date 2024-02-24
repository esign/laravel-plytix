<?php

namespace Esign\Plytix\Facades;

use Illuminate\Support\Facades\Facade;

class PlytixFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'plytix';
    }
}
