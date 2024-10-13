<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithoutTypeDeclared
{
    /**
     * @param \Rougin\Classidy\Fixture\Classes\WithMethod $method
     *
     * @return string
     */
    public function hello($method)
    {
        return $method->hello();
    }
}
