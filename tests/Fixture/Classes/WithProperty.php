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
     * @var string[]
     */
    protected $cols = array(
        'property',
        'argument',
        'classidy',
    );

    /**
     * @link https://roug.in
     *
     * @var integer[]
     */
    protected $types = array();

    /**
     * @var array<string, mixed>
     */
    protected $items = array(
        'page_query_string' => true,
        'use_page_numbers' => true,
        'query_string_segment' => 'p',
        'reuse_query_string' => true,
    );

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
