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
     * @var string|null
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
     * @return string|null
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
            $type = '\\' . $this->class;
        }

        if ($this->declare)
        {
            $type = $this->declare;
        }

        if ($this->isNull())
        {
            $type = $type . '|null';
        }

        return $type;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        if ($this->isNull())
        {
            return 'null';
        }

        /** @var string */
        $parsed = json_encode($this->default);

        $parsed = str_replace('"', '\'', $parsed);

        if (! is_array($this->default))
        {
            // Only applicable if coming from a Property -----
            if ($this instanceof Property && $this->isArray())
            {
                return 'array()';
            }
            // -----------------------------------------------

            return $parsed !== 'null' ? $parsed : null;
        }

        // Try to parse the array items in a property/method ---
        $parsed = 'array(';

        $isList = $this->isArrayList($this->default);

        foreach ($this->default as $index => $value)
        {
            /** @var string */
            $index = json_encode($index);
            $index = str_replace('"', '\'', $index);

            /** @var string */
            $value = var_export($value, true);
            $value = str_replace('"', '\'', $value);

            // Parse value if identified as array -----------
            /** @var string */
            $text = preg_replace('/\s+/', ' ', $value);
            $text = str_replace('array ( ', 'array(', $text);
            $text = str_replace(', )', ')', $text);
            // ----------------------------------------------

            if (! $isList)
            {
                $text = $index . ' => ' . $value;
            }

            $parsed .= "\n[TAB][TAB]" . $text . ',';
        }
        // -----------------------------------------------------

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
     * @param string $class
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
