<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithDataTypes
{
    /**
     * @param float   $grade
     * @param boolean $passer
     *
     * @return string
     */
    public function rating($grade, $passer = true)
    {
        $passed = $passer ? 'I passed!' : 'I failed!';

        return 'My grade is ' . $grade . ' and ' . $passed;
    }
}
