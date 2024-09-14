<?php

namespace Rougin\Classidy;

use Rougin\Classidy\Fixture\Classes\WithMethod;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ArgumentTest extends Testcase
{
    /**
     * @return void
     */
    public function test_argument_as_class()
    {
        $expected = $this->find('WithClassArg');

        $class = $this->newClass('WithClassArg');

        $method = new Method('hello');
        $method->setReturn('string');
        $withMethod = 'Rougin\Classidy\Fixture\Classes\WithMethod';
        $method->addClassArgument($withMethod, 'method');

        $method->setCodeEval(function (WithMethod $method)
        {
            return $method->hello();
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_argument_as_nullable()
    {
        $expected = $this->find('WithNullArg');

        $class = $this->newClass('WithNullArg');

        $method = new Method('hello');
        $method->setReturn('string');
        $method->addStringArgument('name', true);

        $method->setCodeEval(function ($name = null)
        {
            if ($name)
            {
                return 'Hello ' . $name . '!';
            }

            return 'Hello world!';
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }
}
