<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithClassArg
{
    /**
     * @param \Rougin\Classidy\Fixture\Classes\WithMethod $method
     *
     * @return string
     */
    public function hello(WithMethod $method)
    {
        return $method->hello();
    }
}
