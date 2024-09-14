<?php

namespace Rougin\Classidy;

use LegacyPHPUnit\TestCase as Legacy;

/**
 * @codeCoverageIgnore
 *
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Testcase extends Legacy
{
    /** @phpstan-ignore-next-line */
    public function setExpectedException($exception)
    {
        if (method_exists($this, 'expectException'))
        {
            $this->expectException($exception);

            return;
        }

        /** @phpstan-ignore-next-line */
        parent::setExpectedException($exception);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function find($class)
    {
        $path = __DIR__ . '/Fixture/Classes';

        $file = $path . '/' . $class . '.php';

        /** @var string */
        $result = file_get_contents($file);

        return str_replace("\r", '', $result);
    }

    /**
     * @param \Rougin\Classidy\Classidy $class
     *
     * @return string
     */
    protected function make(Classidy $class)
    {
        $generator = new Generator;

        /** @var string */
        $result = $generator->make($class);

        return str_replace("\r", '', $result);
    }

    /**
     * @param string $name
     *
     * @return \Rougin\Classidy\Classidy
     */
    protected function newClass($name)
    {
        $class = new Classidy;

        $namespace = 'Rougin\Classidy\Fixture\Classes';
        $class->setNamespace($namespace);

        $class->setPackage('Fixture');

        $class->setAuthor('Rougin Gutib', 'rougingutib@gmail.com');

        return $class->setName($name);
    }
}
