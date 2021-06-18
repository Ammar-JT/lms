<?php

namespace Tests;

use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function flushRedis(){
        Redis::flushall();
    }
}
