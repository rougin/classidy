<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Method
{
    use Comment;
    use Element;

    const VISIBLE_PUBLIC = 0;

    const VISIBLE_PROTECTED = 1;

    const VISIBLE_PRIVATE = 2;

    /**
     * @var \Rougin\Classidy\Argument[]
     */
    protected $args = array();

    /**
     * @var callable|null
     */
    protected $code = null;

    /**
     * @var string|null
     */
    protected $comment = null;

    /**
     * @var boolean
     */
    protected $eval = false;

    /**
     * @var string|null
     */
    protected $link = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $return = null;

    /**
     * @var boolean
     */
    protected $tag = false;

    /**
     * @var integer
     */
    protected $visible = self::VISIBLE_PUBLIC;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string  $name
     * @param string  $type
     * @param boolean $null
     *
     * @return self
     */
    public function addArrayArgument($name, $type, $null = false)
    {
        $arg = new Argument($name, Argument::TYPE_ARRAY, $null);

        $this->args[] = $arg->setDataType($type);

        return $this;
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
     * @param string  $class
     * @param boolean $null
     *
     * @return self
     */
    public function addClassArgument($name, $class, $null = false)
    {
        $arg = new Argument($name, Argument::TYPE_CLASS, $null);

        $this->args[] = $arg->setClass($class);

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
     * @return boolean
     */
    public function forEval()
    {
        return $this->eval;
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
     * @return string|null
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param callable $code
     *
     * @return self
     */
    public function setCodeEval($code)
    {
        $this->code = $code;

        $this->eval = true;

        return $this;
    }

    /**
     * @param callable $code
     *
     * @return self
     */
    public function setCodeLine($code)
    {
        $this->code = $code;

        $this->eval = false;

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
