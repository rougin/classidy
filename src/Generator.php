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
     * @return string
     */
    public function make(ClassFile $class)
    {
        $file = __DIR__ . '/Template.php';

        $file = new Content($file);

        $file->replace('Template', $class->getName());

        return $file;
    }
}
