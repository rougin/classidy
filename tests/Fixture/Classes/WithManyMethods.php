<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithManyMethods
{
    /**
     * @return string
     */
    public function hello()
    {
        return 'Hello ' . $this->name() . '!';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'world';
    }

    /**
     * @param string[] $items
     *
     * @return string[]
     */
    public function items($items)
    {
        return $items;
    }
}
