<?php

namespace Bastihilger\Entriloquent\Tests;

use Bastihilger\Entriloquent\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
