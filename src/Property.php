<?php

namespace Rougin\Classidy;

/**
 * @method \Rougin\Classidy\Property setClass(string $class)
 *
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Property extends Argument
{
    use Element;

    const VISIBLE_PUBLIC = 0;

    const VISIBLE_PROTECTED = 1;

    const VISIBLE_PRIVATE = 2;

    /**
     * @var string|null
     */
    protected $comment = null;

    /**
     * @var string|null
     */
    protected $link = null;

    /**
     * @var boolean
     */
    protected $tag = false;

    /**
     * @var integer
     */
    protected $visible = self::VISIBLE_PROTECTED;
}
