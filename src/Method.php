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
     * @var array<string, string>[]
     */
    protected $arguments = array();

    /**
     * @var callable
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @param string $datatype
     *
     * @return self
     */
    public function addArgument($name, $datatype)
    {
        $item = array('name' => $name);
        $item['datatype'] = $datatype;

        $this->arguments[] = $item;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getCode()
    {
        $code = $this->code;

        return $code(array());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
}
