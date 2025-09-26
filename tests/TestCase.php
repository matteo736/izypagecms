<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Indica se il seeder predefinito deve essere eseguito prima di ogni test.
     *
     * @var bool
     */
    protected $seed = true;
}
