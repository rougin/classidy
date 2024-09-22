<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Classidy
{
    /**
     * @var string|null
     */
    protected $author = null;

    /**
     * @var class-string|null
     */
    protected $extends;

    /**
     * @var class-string[]
     */
    protected $imports = array();

    /**
     * @var \Rougin\Classidy\Method[]
     */
    protected $methods = array();

    /**
     * @var class-string[]
     */
    protected $notions = array();

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
     * @var \Rougin\Classidy\Property[]
     */
    protected $props = array();

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addBooleanProperty($name, $null = false)
    {
        $this->props[] = new Property($name, Property::TYPE_BOOLEAN, $null);

        return $this;
    }

    /**
     * @param string       $name
     * @param class-string $class
     * @param boolean      $null
     *
     * @return self
     */
    public function addClassProperty($name, $class, $null = false)
    {
        $arg = new Property($name, Property::TYPE_CLASS, $null);

        $this->props[] = $arg->setClass($class);

        return $this;
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addFloatProperty($name, $null = false)
    {
        $this->props[] = new Property($name, Property::TYPE_FLOAT, $null);

        return $this;
    }

    /**
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addIntegerProperty($name, $null = false)
    {
        $this->props[] = new Property($name, Property::TYPE_INTEGER, $null);

        return $this;
    }

    /**
     * @param class-string $interface
     *
     * @return self
     */
    public function addInterface($interface)
    {
        $ref = new \ReflectionClass($interface);

        $parentNamespace = $ref->getNamespaceName();

        $namespace = $this->getNamespace();

        if ($parentNamespace !== $namespace)
        {
            $this->importClass($interface);
        }

        /** @var class-string */
        $shorten = $ref->getShortName();

        $this->notions[] = $shorten;

        return $this;
    }

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
     * @param string  $name
     * @param boolean $null
     *
     * @return self
     */
    public function addStringProperty($name, $null = false)
    {
        $this->props[] = new Property($name, Property::TYPE_STRING, $null);

        return $this;
    }

    /**
     * @return self
     */
    public function asPrivate()
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->asPrivate();

        $this->props[$last] = $property;

        return $this;
    }

    /**
     * @return self
     */
    public function asPublic()
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->asPublic();

        $this->props[$last] = $property;

        return $this;
    }

    /**
     * @return self
     */
    public function asTag()
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->asTag();

        $this->props[$last] = $property;

        return $this;
    }

    /**
     * @param class-string $extends
     *
     * @return self
     */
    public function extendsTo($extends)
    {
        $ref = new \ReflectionClass($extends);

        $parentNamespace = $ref->getNamespaceName();

        $namespace = $this->getNamespace();

        if ($parentNamespace !== $namespace)
        {
            $this->importClass($extends);
        }

        /** @var class-string */
        $shorten = $ref->getShortName();

        $this->extends = $shorten;

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
     * @return class-string|null
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @return class-string[]
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * @return class-string[]
     */
    public function getInterfaces()
    {
        return $this->notions;
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
     * @return string|null
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return \Rougin\Classidy\Property[]
     */
    public function getProperties()
    {
        return $this->props;
    }

    /**
     * @param class-string $class
     *
     * @return self
     */
    public function importClass($class)
    {
        $this->imports[] = $class;

        return $this;
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

    /**
     * @param string|string[] $comment
     *
     * @return self
     */
    public function withComment($comment)
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->setComment($comment);

        $this->props[$last] = $property;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return self
     */
    public function withDefaultValue($default)
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->setDefaultValue($default);

        $this->props[$last] = $property;

        return $this;
    }
}
