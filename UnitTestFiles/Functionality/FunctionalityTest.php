<?php
/*
 *  -------------------------------------------------------------
 *  Copyright (c) 2020
 *  -All Rights Preserved By  Ariful Islam
 *  -If you have any query then knock me at
 *  arif98741@gmail.com
 *  See my profile @ https://github.com/arif98741
 *  ----------------------------------------------------------------
 *
 */

namespace UnitTestFiles\Test;


use Exception;
use PHPUnit\Framework\TestCase;

class FunctionalityTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testTrueAssetsToTrue()
    {
        $condition = true;
        $this->assertTrue(true);
    }
}