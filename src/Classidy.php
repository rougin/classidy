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
     * @var string|null
     */
    protected $comment = null;

    /**
     * @var string|null
     */
    protected $extends;

    /**
     * @var string[]
     */
    protected $imports = array();

    /**
     * @var \Rougin\Classidy\Method[]
     */
    protected $methods = array();

    /**
     * @var string[]
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
     * @var string[]
     */
    protected $traits = array();

    /**
     * @param string  $name
     * @param string  $type
     * @param boolean $null
     *
     * @return self
     */
    public function addArrayProperty($name, $type, $null = false)
    {
        $prop = new Property($name, Property::TYPE_ARRAY, $null);

        $prop->setDataType($type);

        $this->props[] = $prop;

        return $this;
    }

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
     * @param string  $name
     * @param string  $class
     * @param boolean $null
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
     * @param string $interface
     *
     * @return self
     */
    public function addInterface($interface)
    {
        // Extract the base class -----------
        $names = explode('\\', $interface);

        $shorten = $names[count($names) - 1];

        array_pop($names);

        $parent = implode('\\', $names);
        // ----------------------------------

        $namespace = $this->getNamespace();

        if ($parent !== $namespace)
        {
            $this->importClass($interface);
        }

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
     * @param string $trait
     *
     * @return self
     */
    public function addTrait($trait)
    {
        $this->traits[] = $trait;

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
    public function asProtected()
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->asProtected();

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
     * @param string $extends
     *
     * @return self
     */
    public function extendsTo($extends)
    {
        // Extract the base class -----------
        $names = explode('\\', $extends);

        $shorten = $names[count($names) - 1];

        array_pop($names);

        $parent = implode('\\', $names);
        // ----------------------------------

        $namespace = $this->getNamespace();

        if ($parent !== $namespace)
        {
            $this->importClass($extends);
        }

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
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string|null
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @return string[]
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * @return string[]
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
     * @return string[]
     */
    public function getTraits()
    {
        return $this->traits;
    }

    /**
     * @param string $class
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
     * @param string|string[] $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        if (is_array($comment))
        {
            $comment = implode("\n", $comment);
        }

        $this->comment = $comment;

        return $this;
    }

    /**
     * @return self
     */
    public function setEmpty()
    {
        $this->methods = array();

        $this->props = array();

        $this->traits = array();

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

    /**
     * @param string $link
     *
     * @return self
     */
    public function withLink($link)
    {
        $last = count($this->props) - 1;

        $property = $this->props[$last];

        $property->setLink($link);

        $this->props[$last] = $property;

        return $this;
    }
}
