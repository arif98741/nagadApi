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


class Base
{
    private string $base_url = '';


    /**
     * Base constructor
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->base_url;
    }

}