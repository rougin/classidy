<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Generator
{
    /**
     * @var \Rougin\Classidy\Evaluator
     */
    protected $eval;

    /**
     * @var class-string[]
     */
    protected $imports = array();

    /**
     * @var string
     */
    protected $tab = '    ';

    /**
     * @param \Rougin\Classidy\Classidy $class
     *
     * @return \Rougin\Classidy\Output
     */
    public function make(Classidy $class)
    {
        $this->eval = new Evaluator($this->tab);

        $file = $this->getTemplate();

        $file = $this->setClassName($file, $class->getName());

        if ($namespace = $class->getNamespace())
        {
            $file = $this->setNamespace($file, $namespace);
        }

        if ($extends = $class->getExtends())
        {
            $file = $this->setExtends($file, $extends);
        }

        if ($interfaces = $class->getInterfaces())
        {
            $file = $this->setInterfaces($file, $interfaces);
        }

        if ($author = $class->getAuthor())
        {
            $file = $this->setAuthor($file, $author);
        }

        if ($package = $class->getPackage())
        {
            $file = $this->setPackage($file, $package);
        }

        $lines = array();

        if ($props = $class->getProperties())
        {
            $lines = $this->setProperties($lines, $props);
        }

        if ($methods = $class->getMethods())
        {
            if (count($props) > 0)
            {
                $lines[] = '';
            }

            $lines = $this->setMethods($lines, $methods);
        }

        if ($imports = $class->getImports())
        {
            $file = $this->setImports($file, $imports);
        }

        foreach ($lines as $index => $line)
        {
            // Do not add a tab if an empty line ---
            if (empty($line))
            {
                continue;
            }
            // -------------------------------------

            $lines[$index] = $this->tab . $line;
        }

        if (count($lines) === 0)
        {
            return $file;
        }

        $search = $this->tab . '// [CODE]';
        $result = implode("\n", $lines);
        $file->replace($search, $result);

        return $file;
    }

    /**
     * @return \Rougin\Classidy\Output
     */
    protected function getTemplate()
    {
        return new Output(__DIR__ . '/Template.php');
    }

    /**
     * @param \Rougin\Classidy\Argument[] $args
     *
     * @return string
     */
    protected function setArguments($args)
    {
        $items = array();

        foreach ($args as $item)
        {
            // TODO: Refactor this portion as getDefaultValue() ---
            $default = '';

            if ($item->getDefaultValue())
            {
                $default = ' = ' . $item->getDefaultValue();
            }

            if ($item->isNull())
            {
                $default = ' = null';
            }
            // ----------------------------------------------------

            $argument = '$' . $item->getName() . $default;

            if ($class = $item->getClass())
            {
                $ref = new \ReflectionClass($class);

                $this->imports[] = $class;

                $name = $ref->getShortName();

                $argument = $name . ' ' . $argument;
            }

            $items[] = $argument;
        }

        return implode(', ', $items);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string                  $author
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setAuthor(Output $file, $author)
    {
        return $file->replace('Rougin Gutib <rougingutib@gmail.com>', $author);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string                  $name
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setClassName(Output $file, $name)
    {
        return $file->replace('Template', $name);
    }

    /**
     * @param string[]                $lines
     * @param \Rougin\Classidy\Method $method
     *
     * @return string[]
     */
    protected function setCode($lines, Method $method)
    {
        if (! $code = $method->getCode())
        {
            return $lines;
        }

        if ($method->forEval())
        {
            $items = $this->eval->evaluate($code);
        }
        else
        {
            /** @var string[] */
            $items = $code(array());
        }

        foreach ($items as $item)
        {
            // Do not add a tab if an empty line ---
            if (empty($item))
            {
                $lines[] = '';

                continue;
            }
            // -------------------------------------

            $lines[] = $this->tab . $item;
        }

        return $lines;
    }

    /**
     * @param string[]                $lines
     * @param \Rougin\Classidy\Method $method
     *
     * @return string[]
     */
    protected function setComments($lines, Method $method)
    {
        $args = $method->getArguments();

        $comment = $method->getComment();

        $return = $method->getReturn();

        // Criteria for not putting a comment ------------
        if (! $comment && count($args) === 0 && ! $return)
        {
            return $lines;
        }
        // -----------------------------------------------

        $lines[] = '/**';

        if ($comment = $method->getComment())
        {
            $lines[] = ' * ' . $comment;

            if (count($args) > 0 || $return)
            {
                $lines[] = ' *';
            }
        }

        // Get the types, then return the longest data type ---
        $types = array();

        $maxTypeLength = 0;

        foreach ($args as $index => $item)
        {
            // TODO: Refactor this portion as getDataType() ---
            $type = $item->getDataType();

            if ($item->isNull())
            {
                $type = $type . '|null';
            }

            if ($item->getClass())
            {
                $type = '\\' . $type;
            }
            // ------------------------------------------------

            if (strlen($type) > $maxTypeLength)
            {
                $maxTypeLength = strlen($type);
            }

            $types[$index] = $type;
        }
        // ----------------------------------------------------

        foreach ($args as $index => $item)
        {
            $type = $types[$index];

            $type = str_pad($type, $maxTypeLength);

            // TODO: For PHP 7 code, put data type before argument name ---
            // ------------------------------------------------------------

            $lines[] = ' * @param ' . $type . ' $' . $item->getName();
        }

        if (count($args) > 0)
        {
            $lines[] = ' *';
        }

        if ($return)
        {
            $lines[] = ' * @return ' . $return;
        }

        $lines[] = ' */';

        return $lines;
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param class-string            $extends
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setExtends(Output $file, $extends)
    {
        return $file->replace(' /** extends */', ' extends ' . $extends);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param class-string[]          $imports
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setImports(Output $file, $imports)
    {
        /** @var string[] */
        $items = array_merge($this->imports, $imports);
        $items = array_values($items);

        foreach ($items as $index => $item)
        {
            $items[$index] = 'use ' . $item . ';';
        }

        // Sort the imports alphabetically ---
        sort($items);
        // -----------------------------------

        $import = implode(PHP_EOL, $items);

        return $file->replace('// [IMPORTS]', $import);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param class-string[]          $interfaces
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setInterfaces(Output $file, $interfaces)
    {
        $text = ' implements ' . implode(', ', $interfaces);

        return $file->replace(' /** implements */', $text);
    }

    /**
     * @param string[]                  $lines
     * @param \Rougin\Classidy\Method[] $methods
     *
     * @return string[]
     */
    protected function setMethods($lines, $methods)
    {
        foreach ($methods as $index => $method)
        {
            $name = (string) $method->getName();

            $args = $method->getArguments();

            $visibility = $method->getVisibility();

            $args = $this->setArguments($args);

            $lines = $this->setComments($lines, $method);
            $lines[] = $visibility . ' function ' . $name . '(' . $args . ')';
            $lines[] = '{';

            $lines = $this->setCode($lines, $method);

            $lines[] = '}';

            if (array_key_exists($index + 1, $methods))
            {
                $lines[] = '';
            }
        }

        return $lines;
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string                  $namespace
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setNamespace(Output $file, $namespace)
    {
        return $file->replace('namespace Rougin\Classidy', 'namespace ' . $namespace);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string                  $package
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setPackage(Output $file, $package)
    {
        return $file->replace('@package Classidy', '@package ' . $package);
    }

    /**
     * @param string[]                    $lines
     * @param \Rougin\Classidy\Property[] $props
     *
     * @return string[]
     */
    protected function setProperties($lines, $props)
    {
        foreach ($props as $index => $item)
        {
            // TODO: Refactor this portion as getDataType() ---
            $type = $item->getDataType();

            if ($item->isNull())
            {
                $type = $type . '|null';
            }

            if ($item->getClass())
            {
                $type = '\\' . $type;
            }
            // ------------------------------------------------

            $visibility = $item->getVisibility();

            // TODO: Refactor this portion as getDefaultValue() ---
            $default = '';

            if ($item->getDefaultValue())
            {
                $default = ' = ' . $item->getDefaultValue();
            }

            if ($item->isNull())
            {
                $default = ' = null';
            }
            // ----------------------------------------------------

            $name = '$' . $item->getName() . $default . ';';

            $lines[] = '/**';
            $lines[] = ' * @var ' . $type;
            $lines[] = ' */';
            $lines[] = $visibility . ' ' . $name;

            if (array_key_exists($index + 1, $props))
            {
                $lines[] = '';
            }
        }

        return $lines;
    }
}
