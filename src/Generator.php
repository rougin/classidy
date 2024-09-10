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
            $package = 'Classidy';
            $file->replace($package, $class->getPackage());
        }

        $tab = '    ';
        $lines = array();

        foreach ($class->getMethods() as $name => $method)
        {
            $lines[] = $tab . 'public function ' . $name . '()';
            $lines[] = $tab . '{';

            /** @var string[] */
            $items = $method(array());

            foreach ($items as $item)
            {
                $lines[] = $tab . $tab . $item;
            }

            $lines[] = $tab . '}';
        }

        $method = $tab . '// [METHODS]';
        $result = implode("\n", $lines);
        $file->replace($method, $result);

        return $file;
    }
}
