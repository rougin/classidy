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

    const TYPE_ARRAY = 5;

    /**
     * @var class-string|null
     */
    protected $class;

    /**
     * @var string|null
     */
    protected $declare = null;

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
     * @return class-string|null
     */
    public function getClass()
    {
        return $this->class;
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

        if ($this->declare)
        {
            $type = $this->declare;
        }

        return $type;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        /** @var string */
        $parsed = json_encode($this->default);

        $parsed = str_replace('"', '\'', $parsed);

        if (! is_array($this->default))
        {
            return $parsed !== 'null' ? $parsed : null;
        }

        $parsed = 'array(';

        $isList = $this->isArrayList($this->default);

        foreach ($this->default as $index => $value)
        {
            /** @var string */
            $index = json_encode($index);
            $index = str_replace('"', '\'', $index);

            /** @var string */
            $value = json_encode($value);
            $value = str_replace('"', '\'', $value);

            $text = $value;

            if (! $isList)
            {
                $text = $index . ' => ' . $value;
            }

            $parsed .= "\n[TAB][TAB]" . $text . ',';
        }

        return $parsed . "\n[TAB])";
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function isArray()
    {
        return $this->type === self::TYPE_ARRAY;
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
     * @param string $declare
     *
     * @return self
     */
    public function setDataType($declare)
    {
        $this->declare = $declare;

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

    /**
     * @param mixed[] $array
     *
     * @return boolean
     */
    protected function isArrayList($array)
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
