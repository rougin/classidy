<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ClassFile
{
    /**
     * @var string|null
     */
    protected $extends;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $extends
     *
     * @return self
     */
    public function extends($extends)
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtends()
    {
        return $this->extends;
    }
}
