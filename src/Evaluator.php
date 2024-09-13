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

        // Return the initial string position of entire callback ---
        $prev = $items[$ref->getStartLine() - 1];

        $index = $this->getStartIndex($prev);
        // ---------------------------------------------------------

        $start = $ref->getStartLine();
        $end = $ref->getEndLine();

        for ($i = $start; $i < $end; $i++)
        {
            $parsed = $this->parseLine($items[$i], $index);

            // Always skip the first and last lines of a callback ---
            $isFirst = $i === $start;
            $isLast = $i === ($end - 1);

            if (($isFirst || $isLast) && ! $parsed)
            {
                continue;
            }
            // ------------------------------------------------------

            $lines[] = $parsed;
        }

        return $lines;
    }

    /**
     * @param string $line
     *
     * @return integer
     */
    protected function getStartIndex($line)
    {
        $result = preg_match('/^\s*(\S)/m', $line, $matches);

        /** @var integer */
        return $result === false ? 0 : strpos($line, $matches[1]);
    }

    /**
     * @param string  $line
     * @param integer $start
     *
     * @return string
     */
    protected function parseLine($line, $start = 0)
    {
        $line = substr($line, $start, strlen($line) - 1);

        $spaces = strlen($this->tab);

        $line = rtrim(str_replace(PHP_EOL, '', $line));

        $length = strlen($line);

        return substr($line, $spaces, $length);
    }
}
