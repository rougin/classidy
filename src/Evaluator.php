<?php

namespace Rougin\Classidy;

use ReflectionFunction;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Evaluator
{
    /**
     * @var string
     */
    protected $tab = '    ';

    /**
     * @param string $tab
     */
    public function __construct($tab = '    ')
    {
        $this->tab = $tab;
    }

    /**
     * @link https://stackoverflow.com/a/25586142
     *
     * @param callable $code
     *
     * @return string[]
     */
    public function evaluate($code)
    {
        /** @var \Closure $code */
        $ref = new ReflectionFunction($code);

        /** @var string[] */
        $items = file((string) $ref->getFileName());

        $lines = array();

        for ($i = $ref->getStartLine(); $i < $ref->getEndLine(); $i++)
        {
            $parsed = $this->parseLine($items[$i]);

            // Always skip the first and last lines of a callback ---
            $isFirst = $i === $ref->getStartLine();
            $isLast = $i === ($ref->getEndLine() - 1);
            $isEmpty = $parsed === false || $parsed === "\n";

            if (($isFirst || $isLast) && $isEmpty)
            {
                continue;
            }
            // -------------------------------------------------------

            $lines[] = $parsed;
        }

        return $lines;
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function parseLine($line)
    {
        $spaces = strlen($this->tab);

        $line = str_replace(PHP_EOL, '', $line);

        $length = strlen($line);

        return substr($line, $spaces, $length);
    }
}
