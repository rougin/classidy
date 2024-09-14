<?php

namespace Rougin\Classidy\Fixture\Classes;

/**
 * @package Fixture
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WithProperty
{
    /**
     * @var integer|null
     */
    public $age = null;

    /**
     * @var string
     */
    protected $name = 'Classidy';

    /**
     * @var \Rougin\Classidy\Fixture\Classes\WithMethod
     */
    protected $with;

    /**
     * @var boolean
     */
    private $loud = false;

    /**
     * @var float
     */
    protected $grade;

    /**
     * @return boolean
     */
    public function shout()
    {
        return $this->loud;
    }
}
