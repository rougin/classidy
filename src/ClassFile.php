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
