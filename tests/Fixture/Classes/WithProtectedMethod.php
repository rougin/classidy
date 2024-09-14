<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithProtectedMethod
{
    /**
     * @return string
     */
    protected function hello()
    {
        return 'Hello world!';
    }
}
