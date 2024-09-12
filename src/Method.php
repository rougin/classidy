<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Method
{
    /**
     * @var \Rougin\Classidy\Argument[]
     */
    protected $args = array();

    /**
     * @var callable|null
     */
    protected $code = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $return = 'void';

    /**
     * @var string|null
     */
    protected $text = null;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addBooleanArgument($name, $null = false)
    {
        $this->args[] = new Argument($name, Argument::TYPE_BOOLEAN, $null);

        return $this;
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addFloatArgument($name, $null = false)
    {
        $this->args[] = new Argument($name, Argument::TYPE_FLOAT, $null);

        return $this;
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addIntegerArgument($name, $null = false)
    {
        $this->args[] = new Argument($name, Argument::TYPE_INTEGER, $null);

        return $this;
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addStringArgument($name, $null = false)
    {
        $this->args[] = new Argument($name, Argument::TYPE_STRING, $null);

        return $this;
    }

    /**
     * @return \Rougin\Classidy\Argument[]
     */
    public function getArguments()
    {
        return $this->args;
    }

    /**
     * @return callable|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param callable $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

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
     * @param string $return
     *
     * @return self
     */
    public function setReturn($return)
    {
        $this->return = $return;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return self
     */
    public function withDefaultValue($default)
    {
        $last = count($this->args) - 1;

        $argument = $this->args[$last];

        $argument->setDefaultValue($default);

        $this->args[$last] = $argument;

        return $this;
    }
}
