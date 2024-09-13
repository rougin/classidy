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
    protected $author = null;

    /**
     * @var string|null
     */
    protected $extends;

    /**
     * @var \Rougin\Classidy\Method[]
     */
    protected $methods = array();

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $namespace = null;

    /**
     * @var string|null
     */
    protected $package = null;

    /**
     * @param \Rougin\Classidy\Method $method
     *
     * @return self
     */
    public function addMethod(Method $method)
    {
        $this->methods[] = $method;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $extends
     *
     * @return self
     */
    public function extendsTo($extends)
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string|null
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string|null
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @return \Rougin\Classidy\Method[]
     */
    public function getMethods()
    {
        return $this->methods;
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
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string      $name
     * @param string|null $email
     *
     * @return self
     */
    public function setAuthor($name, $email = null)
    {
        if ($email)
        {
            $email = '<' . $email . '>';
        }

        $this->author = trim($name . ' ' . $email);

        return $this;
    }

    /**
     * @return self
     */
    public function setConstruct()
    {
        $this->methods[] = new Method('__construct');

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
     * @param string $namespace
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $package
     *
     * @return self
     */
    public function setPackage($package)
    {
        $this->package = $package;

        return $this;
    }
}
