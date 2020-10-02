<?php
/*
 *
 *  * -------------------------------------------------------------
 *  * Copyright (c) 2020
 *  * -created by Ariful Islam
 *  * -All Rights Preserved By
 *  *     Ariful Islam
 *  *    www.phpdark.com
 *  * -If you have any query then knock me at
 *  * arif98741@gmail.com
 *  * See my profile @ https://github.com/arif98741
 *  * ----------------------------------------------------------------
 *
 */

namespace NagadApi;


class RequestHandler
{
    /**
     * @var
     */
    private $helper;


    /**
     * RequestHandler constructor.
     * @param Base $base
     */
    public function __construct(Base $base)
    {
        $this->helper = new Helper();
    }

    public function init()
    {
        
    }
}