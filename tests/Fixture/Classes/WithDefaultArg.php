<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithDefaultArg
{
    /**
     * @param integer $age
     *
     * @return string
     */
    public function age($age = 29)
    {
        return 'My age is ' . $age;
    }
}
