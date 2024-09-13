<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithArgument
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function hello($name)
    {
        return 'Hello ' . $name . '!';
    }
}
