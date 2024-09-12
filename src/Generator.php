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
        $file = __DIR__ . '/Template.php';

        $file = new Content($file);

        $name = 'Template';
        $file->replace($name, $class->getName());

        if ($class->getAuthor())
        {
            $author = 'Rougin Gutib <rougingutib@gmail.com>';
            $file->replace($author, $class->getAuthor());
        }

        if ($class->getPackage())
        {
            $package = '@package Classidy';
            $text = '@package ' . $class->getPackage();
            $file->replace($package, $text);
        }

        $lines = array();

        $eval = new Evaluator($this->tab);

        foreach ($class->getMethods() as $method)
        {
            $name = (string) $method->getName();

            $args = $method->getArguments();

            $args = $this->setArguments($args);

            $lines = $this->setComments($lines, $method);
            $lines[] = 'public function ' . $name . '(' . $args . ')';
            $lines[] = '{';

            if ($code = $method->getCode())
            {
                $items = $eval->evaluate($code);

                foreach ($items as $item)
                {
                    $lines[] = $this->tab . $item;
                }
            }

            $lines[] = '}';
        }

        foreach ($lines as $index => $line)
        {
            $lines[$index] = $this->tab . $line;
        }

        $method = $this->tab . '// [METHODS]';
        $result = implode("\n", $lines);
        $file->replace($method, $result);

        return $file;
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
     * @param string[]                $lines
     * @param \Rougin\Classidy\Method $method
     *
     * @return string[]
     */
    public function setComments($lines, Method $method)
    {
        $args = $method->getArguments();

        $lines[] = '/**';

        if ($text = $method->getText())
        {
            $lines[] = ' * ' . $text;

            if (count($args) > 0)
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

        $lines[] = ' * @return ' . $method->getReturn();
        $lines[] = ' */';

        return $lines;
    }
}
