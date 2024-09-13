<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithNullArg
{
    /**
     * @param string|null $name
     *
     * @return string
     */
    public function hello($name = null)
    {
        if ($name)
        {
            return 'Hello ' . $name . '!';
        }

        return 'Hello world!';
    }
}
