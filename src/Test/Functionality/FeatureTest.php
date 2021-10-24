<?php

namespace Xenon\NagadApi\Test\Functionality;

use Exception;
use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    /**
     * @throws Exception
     * @throws Exception
     */
    public function testTrueAssetsToTrue()
    {
        $condition = true;
        $this->assertTrue(true);
    }

}