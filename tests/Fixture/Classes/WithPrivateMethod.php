<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithPrivateMethod
{
    /**
     * @return string
     */
    public function hello()
    {
        return $this->greet();
    }

    /**
     * @return string
     */
    private function greet()
    {
        return 'Hello world!';
    }
}
