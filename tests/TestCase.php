<?php

namespace Gbuckingham89\ValetAssistant\Tests;

use Gbuckingham89\ValetAssistant\ValetAssistantServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ValetAssistantServiceProvider::class,
        ];
    }
}
