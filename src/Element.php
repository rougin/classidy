<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
trait Element
{
    // const VISIBLE_PUBLIC = 0;

    // const VISIBLE_PROTECTED = 1;

    // const VISIBLE_PRIVATE = 2;

    /**
     * @return self
     */
    public function asPrivate()
    {
        $this->visible = 2;

        return $this;
    }

    /**
     * @return self
     */
    public function asProtected()
    {
        $this->visible = 1;

        return $this;
    }

    /**
     * @return self
     */
    public function asPublic()
    {
        $this->visible = 0;

        return $this;
    }

    /**
     * @return self
     */
    public function asTag()
    {
        $this->tag = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        if ($this->visible === 2)
        {
            return 'private';
        }

        if ($this->visible === 1)
        {
            return 'protected';
        }

        return 'public';
    }

    /**
     * @return boolean
     */
    public function isTag()
    {
        return $this->tag;
    }

    /**
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
