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
     * @var string[]
     */
    protected $imports = array();

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var string
     */
    protected $tab = '    ';

    /**
     * @var string[]
     */
    protected $tags = array();

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
            $this->namespace = $namespace;

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

        $lines = array();

        if ($traits = $class->getTraits())
        {
            $lines = $this->setTraits($lines, $traits);
        }

        if ($props = $class->getProperties())
        {
            if (count($lines) > 0)
            {
                $lines[] = '';
            }

            $lines = $this->setProperties($lines, $props);
        }

        if ($methods = $class->getMethods())
        {
            if (count($lines) > 0)
            {
                $lines[] = '';
            }

            $lines = $this->setMethods($lines, $methods);
        }

        if ($imports = $class->getImports())
        {
            $file = $this->setImports($file, $imports);
        }

        if ($package = $class->getPackage())
        {
            $this->setPackage($package);
        }

        if ($author = $class->getAuthor())
        {
            $this->setAuthor($author);
        }

        $comment = $class->getComment();

        // Add tags and comments to class if available -----------
        if (count($this->tags) > 0 || $comment)
        {
            $tags = array('/**');

            if ($comment)
            {
                $comments = explode("\n", $comment);

                foreach ($comments as $value)
                {
                    $tags[] = ' *' . ($value ? ' ' : '') . $value;
                }

                if (count($this->tags) > 0)
                {
                    $tags[] = ' *';
                }
            }

            foreach ($this->tags as $tag)
            {
                if ($tag === '')
                {
                    $tags[] = ' *';

                    continue;
                }

                $tags[] = ' * ' . $tag;
            }

            $tags[] = ' */';

            $tag = implode("\n", $tags);

            $file = $file->replace('// [DETAILS]', $tag);
        }
        // -------------------------------------------------------

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

        return $file->replace('[TAB]', $this->tab);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function getBaseClass($class)
    {
        $names = explode('\\', $class);

        $names = array_values(array_filter($names));

        $name = $names[count($names) - 1];

        return count($names) === 1 ? '\\' . $name : $name;
    }

    /**
     * @return \Rougin\Classidy\Output
     */
    protected function getTemplate()
    {
        return new Output(__DIR__ . '/Template.php');
    }

    /**
     * @param string $class
     *
     * @return boolean
     */
    protected function isClassImportable($class)
    {
        $imported = in_array($class, $this->imports);

        $names = explode('\\', $class);

        $names = array_values(array_filter($names));

        $hasNamespace = count($names) > 1;

        return ! $imported && $hasNamespace;
    }

    /**
     * @param \Rougin\Classidy\Argument[] $args
     * @param boolean                     $declare
     *
     * @return string
     */
    protected function setArguments($args, $declare = false)
    {
        $items = array();

        foreach ($args as $item)
        {
            if ($default = $item->getDefaultValue())
            {
                $default = ' = ' . $default;
            }

            $argument = '$';

            if ($declare && ! $item->getClass())
            {
                $argument = $item->getDataType() . ' $';
            }

            $argument .= $item->getName() . $default;

            $name = null;

            if ($class = $item->getClass())
            {
                // Extract the base class ----------
                $name = $this->getBaseClass($class);
                // ---------------------------------

                if ($this->isClassImportable($class))
                {
                    $this->imports[] = $class;
                }
            }

            if ($name && $item->isTypeDeclared())
            {
                $argument = $name . ' ' . $argument;
            }

            $items[] = $argument;
        }

        return implode(', ', $items);
    }

    /**
     * @param string $author
     *
     * @return self
     */
    protected function setAuthor($author)
    {
        if (count($this->tags) > 0)
        {
            $this->tags[] = '';
        }

        $this->tags[] = '@author ' . $author;

        return $this;
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

        $link = $method->getLink();

        // Criteria for not putting a comment -----------------------
        if (! $comment && count($args) === 0 && ! $return && ! $link)
        {
            return $lines;
        }
        // ----------------------------------------------------------

        $lines[] = '/**';

        if ($comment)
        {
            $comments = explode("\n", $comment);

            foreach ($comments as $item)
            {
                $lines[] = ' * ' . $item;
            }
        }

        if ($link)
        {
            $lines[] = ' * @link ' . $link;
        }

        if (($comment || $link) && (count($args) > 0 || $return))
        {
            $lines[] = ' *';
        }

        // Get the types, then return the longest data type ---
        $types = array();

        $maxTypeLength = 0;

        foreach ($args as $index => $item)
        {
            $type = $item->getDataType();

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
     * @param string                  $extends
     *
     * @return \Rougin\Classidy\Output
     */
    protected function setExtends(Output $file, $extends)
    {
        return $file->replace(' /** extends */', ' extends ' . $extends);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string[]                $imports
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

        $import = implode("\n", $items);

        return $file->replace('// [IMPORTS]', $import);
    }

    /**
     * @param \Rougin\Classidy\Output $file
     * @param string[]                $interfaces
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
        $maxTypeLength = 4;

        $withTags = false;

        foreach ($methods as $index => $item)
        {
            $return = $item->getReturn();

            if ($item->isTag())
            {
                $withTags = true;

                if ($return && strlen($return) > $maxTypeLength)
                {
                    $maxTypeLength = strlen($return);
                }

                continue;
            }

            $name = (string) $item->getName();

            $args = $item->getArguments();

            $visibility = $item->getVisibility();

            $args = $this->setArguments($args);

            $lines = $this->setComments($lines, $item);
            $lines[] = $visibility . ' function ' . $name . '(' . $args . ')';
            $lines[] = '{';

            $lines = $this->setCode($lines, $item);

            $lines[] = '}';

            if (array_key_exists($index + 1, $methods))
            {
                $lines[] = '';
            }
        }

        if ($withTags && count($this->tags) > 0)
        {
            $this->tags[] = '';
        }

        foreach ($methods as $index => $item)
        {
            if (! $item->isTag())
            {
                continue;
            }

            // Specify the return type of the method ---
            $return = $item->getReturn();

            $return = $return ? $return : 'void';

            $return = str_pad($return, $maxTypeLength);
            // -----------------------------------------

            $args = $item->getArguments();

            $args = $this->setArguments($args, true);

            $name = ' ' . $item->getName() . '(' . $args . ')';

            $this->tags[] = '@method ' . $return . $name;
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
     * @param string $package
     *
     * @return self
     */
    protected function setPackage($package)
    {
        if (count($this->tags) > 0)
        {
            $this->tags[] = '';
        }

        $this->tags[] = '@package ' . $package;

        return $this;
    }

    /**
     * @param string[]                    $lines
     * @param \Rougin\Classidy\Property[] $props
     *
     * @return string[]
     */
    protected function setProperties($lines, $props)
    {
        $maxTypeLength = 0;

        $types = array();

        foreach ($props as $index => $item)
        {
            $type = $item->getDataType();

            $types[$index] = $type;

            if ($item->isTag())
            {
                if (strlen($type) > $maxTypeLength)
                {
                    $maxTypeLength = strlen($type);
                }

                continue;
            }

            $visibility = $item->getVisibility();

            if ($default = $item->getDefaultValue())
            {
                $default = ' = ' . $default;
            }

            $name = '$' . $item->getName() . $default . ';';

            $lines[] = '/**';

            if ($comment = $item->getComment())
            {
                $comments = explode("\n", $comment);

                foreach ($comments as $value)
                {
                    $lines[] = ' *' . ($value ? ' ' : '') . $value;
                }

                $lines[] = ' *';
            }

            if ($link = $item->getLink())
            {
                $lines[] = ' * @link ' . $link;

                $lines[] = ' *';
            }

            $lines[] = ' * @var ' . $type;
            $lines[] = ' */';
            $lines[] = $visibility . ' ' . $name;

            if (array_key_exists($index + 1, $props))
            {
                $lines[] = '';
            }
        }

        foreach ($props as $index => $item)
        {
            if (! $item->isTag())
            {
                continue;
            }

            $type = str_pad($types[$index], $maxTypeLength);

            $name = ' $' . $item->getName();

            $this->tags[] = '@property ' . $type . $name;
        }

        return $lines;
    }

    /**
     * @param string[] $lines
     * @param string[] $traits
     *
     * @return string[]
     */
    protected function setTraits($lines, $traits)
    {
        foreach ($traits as $trait)
        {
            // Extract the base class ----------
            $name = $this->getBaseClass($trait);
            // ---------------------------------

            if ($this->isClassImportable($trait))
            {
                $this->imports[] = $trait;
            }

            $lines[] = 'use ' . $name . ';';
        }

        return $lines;
    }
}
