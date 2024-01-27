<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param \Illuminate\Testing\TestResponse $response
     * @return array
     */
    protected function getResultAry(TestResponse $response): array
    {
        return json_decode($response->baseResponse->content(), true);
    }
}
