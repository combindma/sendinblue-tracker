<?php

namespace Combindma\SendinBlueTracker\Facades;

use Illuminate\Support\Facades\Facade;


class SendinBlueTracker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sendinblue-tracker';
    }
}
