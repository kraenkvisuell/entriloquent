<?php

namespace Kraenkvisuell\Entriloquent\Tests;

use Kraenkvisuell\Entriloquent\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
