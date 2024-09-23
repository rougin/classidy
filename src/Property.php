<?php

namespace Rougin\Classidy;

/**
 * @method \Rougin\Classidy\Property setClass(class-string $class)
 *
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Property extends Argument
{
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

    /**
     * TODO: Rework as one functionality from Method.
     *
     * @return self
     */
    public function asPrivate()
    {
        $this->visible = self::VISIBLE_PRIVATE;

        return $this;
    }

    /**
     * TODO: Rework as one functionality from Method.
     *
     * @return self
     */
    public function asPublic()
    {
        $this->visible = self::VISIBLE_PUBLIC;

        return $this;
    }

    /**
     * TODO: Rework as one functionality from Method.
     *
     * @return self
     */
    public function asTag()
    {
        $this->tag = true;

        return $this;
    }

    /**
     * TODO: Rework as one functionality from Property.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * TODO: Rework as one functionality from Property.
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * TODO: Rework as one functionality from Method.
     *
     * @return string
     */
    public function getVisibility()
    {
        if ($this->visible === self::VISIBLE_PRIVATE)
        {
            return 'private';
        }

        if ($this->visible === self::VISIBLE_PROTECTED)
        {
            return 'protected';
        }

        return 'public';
    }

    /**
     * TODO: Rework as one functionality from Method.
     *
     * @return boolean
     */
    public function isTag()
    {
        return $this->tag;
    }

    /**
     * TODO: Rework as one functionality from Property.
     *
     * @param string|string[] $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        if (is_array($comment))
        {
            $comment = implode("\n", $comment);
        }

        $this->comment = $comment;

        return $this;
    }

    /**
     * TODO: Rework as one functionality from Property.
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }
}
