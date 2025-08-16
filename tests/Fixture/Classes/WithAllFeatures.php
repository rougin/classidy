<?php

namespace Rougin\Classidy\Fixture\Classes;

use Rougin\Classidy\Fixture\Miserable;
use Rougin\Classidy\Fixture\Traitable;

/**
 * A class with all features to Classidy.
 *
 * @method void test()
 *
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithAllFeatures extends WithMethod implements Miserable
{
    use Traitable;

    /**
     * @var integer
     */
    protected $type;

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
