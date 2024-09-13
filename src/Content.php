<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Content
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        /** @var string */
        $file = file_get_contents($file);

        $this->file = $file;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $file = $this->file;

        $lines = explode("\n", $file);

        $texts = array('// [IMPORTS]');
        $texts[] = '// [METHODS]';
        $texts[] = '// [PROPERTIES]';

        foreach ($lines as $index => $line)
        {
            foreach ($texts as $text)
            {
                if (strpos($line, $text) === false)
                {
                    continue;
                }

                unset($lines[$index]);

                if ($text === '// [IMPORTS]')
                {
                    unset($lines[$index - 1]);
                }
            }
        }

        $class = implode(PHP_EOL, $lines);

        // Remove the placeholders for class details ----------
        $class = str_replace(' /** extends */', '', $class);

        $class = str_replace(' /** implements */', '', $class);
        // ----------------------------------------------------

        return $class;
    }

    /**
     * @param string $search
     * @param string $text
     *
     * @return self
     */
    public function replace($search, $text)
    {
        $this->file = str_replace($search, $text, $this->file);

        return $this;
    }
}
