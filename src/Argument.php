<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Argument
{
    const TYPE_STRING = 0;

    const TYPE_INTEGER = 1;

    const TYPE_BOOLEAN = 2;

    const TYPE_FLOAT = 3;

    const TYPE_CLASS = 4;

    /**
     * @var class-string|null
     */
    protected $class;

    /**
     * @var mixed|null
     */
    protected $default = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var boolean
     */
    protected $null;

    /**
     * @var integer
     */
    protected $type;

    /**
     * @param string  $name
     * @param integer $type
     * @param boolean $null
     */
    public function __construct($name, $type, $null = false)
    {
        $this->name = $name;

        $this->type = $type;

        $this->null = $null;
    }

    /**
     * @return string
     */
    public function getDataType()
    {
        $type = '';

        if ($this->getType() === self::TYPE_STRING)
        {
            $type = 'string';
        }

        if ($this->getType() === self::TYPE_INTEGER)
        {
            $type = 'integer';
        }

        if ($this->getType() === self::TYPE_BOOLEAN)
        {
            $type = 'boolean';
        }

        if ($this->getType() === self::TYPE_FLOAT)
        {
            $type = 'float';
        }

        if ($this->getType() === self::TYPE_CLASS)
        {
            /** @var string */
            $type = $this->class;
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return class-string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        /** @var string */
        $parsed = json_encode($this->default);

        $parsed = str_replace('"', '\'', $parsed);

        return $parsed !== 'null' ? $parsed : null;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isNull()
    {
        return $this->null;
    }

    /**
     * @param class-string $class
     *
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return self
     */
    public function setDefaultValue($default)
    {
        $this->default = $default;

        return $this;
    }
}
