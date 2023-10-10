<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class BaseTestcase extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    public function getRandomNumber()
    {
        return hexdec(uniqid());
    }
}
