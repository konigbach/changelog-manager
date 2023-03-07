<?php

namespace Konigbach\ChangelogManager\Tests;

use Konigbach\ChangelogManager\ChangelogManagerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [ChangelogManagerServiceProvider::class];
    }
}
