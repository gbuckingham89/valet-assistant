<?php

namespace Gbuckingham89\ValetAssistant\Facades;

use Gbuckingham89\ValetAssistant\ValetAssistant as TheValetAssistant;
use Illuminate\Support\Facades\Facade;

class ValetAssistant extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return TheValetAssistant::class;
    }
}
