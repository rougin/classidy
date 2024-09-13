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
     * @param \Rougin\Classidy\ClassFile $class
     *
     * @return \Rougin\Classidy\Content
     */
    public function make(ClassFile $class)
    {
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

        if ($author = $class->getAuthor())
        {
            $file = $this->setAuthor($file, $author);
        }

        if ($package = $class->getPackage())
        {
            $file = $this->setPackage($file, $package);
        }

        if ($imports = $class->getImports())
        {
            $file = $this->setImports($file, $imports);
        }

        $lines = array();

        $this->eval = new Evaluator($this->tab);

        $methods = $class->getMethods();

        foreach ($methods as $index => $method)
        {
            $name = (string) $method->getName();

            $args = $method->getArguments();

            $args = $this->setArguments($args);

            $lines = $this->setComments($lines, $method);
            $lines[] = 'public function ' . $name . '(' . $args . ')';
            $lines[] = '{';

            $lines = $this->setCode($lines, $method);

            $lines[] = '}';

            if (array_key_exists($index + 1, $methods))
            {
                $lines[] = '';
            }
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

        $method = $this->tab . '// [METHODS]';
        $result = implode("\n", $lines);
        $file->replace($method, $result);

        return $file;
    }

    /**
     * @return \Rougin\Classidy\Content
     */
    protected function getTemplate()
    {
        return new Content(__DIR__ . '/Template.php');
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
            $default = '';

            if ($item->getDefaultValue())
            {
                $default = ' = ' . $item->getDefaultValue();
            }

            if ($item->isNull())
            {
                $default = ' = null';
            }

            $name = $item->getName();

            $items[] = '$' . $name . $default;
        }

        return implode(', ', $items);
    }

    /**
     * @param \Rougin\Classidy\Content $file
     * @param string                   $author
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setAuthor(Content $file, $author)
    {
        return $file->replace('Rougin Gutib <rougingutib@gmail.com>', $author);
    }

    /**
     * @param \Rougin\Classidy\Content $file
     * @param string                   $name
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setClassName(Content $file, $name)
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

        // Get the types first to get the longest data type string ---
        $types = array();

        $maxTypeLength = 0;

        foreach ($args as $index => $item)
        {
            $type = $item->getDataType();

            if ($item->isNull())
            {
                $type = $type . '|null';
            }

            if (strlen($type) > $maxTypeLength)
            {
                $maxTypeLength = strlen($type);
            }

            $types[$index] = $type;
        }
        // -----------------------------------------------------------

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
     * @param \Rougin\Classidy\Content $file
     * @param class-string             $extends
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setExtends(Content $file, $extends)
    {
        return $file->replace(' /** extends */', ' extends ' . $extends);
    }

    /**
     * @param \Rougin\Classidy\Content $file
     * @param class-string[]           $imports
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setImports(Content $file, $imports)
    {
        foreach ($imports as $index => $item)
        {
            $imports[$index] = 'use ' . $item . ';';
        }

        $import = implode(PHP_EOL, $imports);

        return $file->replace('// [IMPORTS]', $import);
    }

    /**
     * @param \Rougin\Classidy\Content $file
     * @param string                   $namespace
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setNamespace(Content $file, $namespace)
    {
        return $file->replace('namespace Rougin\Classidy', 'namespace ' . $namespace);
    }

    /**
     * @param \Rougin\Classidy\Content $file
     * @param string                   $package
     *
     * @return \Rougin\Classidy\Content
     */
    protected function setPackage(Content $file, $package)
    {
        return $file->replace('@package Classidy', '@package ' . $package);
    }
}
