<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Content
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        /** @var string */
        $file = file_get_contents($file);

        $this->file = $file;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->file;
    }

    /**
     * @param string $search
     * @param string $text
     *
     * @return self
     */
    public function replace($search, $text)
    {
        $this->file = str_replace($search, $text, $this->file);

        return $this;
    }
}
