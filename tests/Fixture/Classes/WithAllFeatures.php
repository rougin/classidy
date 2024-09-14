<?php

namespace Rougin\Classidy\Fixture\Classes;

use Rougin\Classidy\Fixture\Classable;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithAllFeatures extends WithMethod implements Classable
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function greet($name = 'world')
    {
        return 'Hello ' . $name . '!';
    }

    /**
     * @return string
     */
    public function sample()
    {
        $text = 'text';

        return 'This is a sample ' . $text;
    }
}
