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
     * Age of the specified class.
     *
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
     * Shouts from the specified class.
     * If enabled, it will be on all upper cases.
     *
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
