<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ClassTest extends Testcase
{
    /**
     * @return void
     */
    public function test_class_with_method()
    {
        $expected = $this->find('WithMethod');

        $class = $this->newClass('WithMethod');

        $method = new Method('hello');
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return 'Hello world!';
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_code_as_lines()
    {
        $expected = $this->find('WithComments');

        $class = $this->newClass('WithComments');

        $method = new Method('hello');
        $method->setComment('Returns the text "Hello world!".');
        $method->setReturn('string');
        $method->setCodeLine(function ($lines)
        {
            $lines[] = 'return \'Hello world!\';';

            return $lines;
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_with_argument()
    {
        $expected = $this->find('WithArgument');

        $class = $this->newClass('WithArgument');

        $method = new Method('hello');
        $method->setReturn('string');

        $method->addStringArgument('name');

        $method->setCodeEval(function ($name)
        {
            return 'Hello ' . $name . '!';
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_with_data_types()
    {
        $expected = $this->find('WithDataTypes');

        $class = $this->newClass('WithDataTypes');

        $method = new Method('rating');
        $method->setReturn('string');

        $method->addFloatArgument('grade');
        $method->addBooleanArgument('passer')
            ->withDefaultValue(true);

        $method->setCodeEval(function ($grade, $passer = true)
        {
            $passed = $passer ? 'I passed!' : 'I failed!';

            return 'My grade is ' . $grade . ' and ' . $passed;
        });

        $actual = $this->make($class->addMethod($method));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_with_default_argument()
    {
        $expected = $this->find('WithDefaultArg');

        $class = $this->newClass('WithDefaultArg');

        $method = new Method('age');
        $method->setReturn('string');

        $method->addIntegerArgument('age')
            ->withDefaultValue(29);

        $method->setCodeEval(function ($age = 29)
        {
            return 'My age is ' . $age;
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

    /**
     * @return void
     */
    public function test_class_with_empty_construct()
    {
        $expected = $this->find('WithEmptyConstruct');

        $class = $this->newClass('WithEmptyConstruct');

        $actual = $this->make($class->setConstruct());

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_class_with_many_methods()
    {
        $expected = $this->find('WithManyMethods');

        $class = $this->newClass('WithManyMethods');

        $method = new Method('hello');
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return 'Hello ' . $this->name() . '!';
        });
        $class->addMethod($method);

        $method = new Method('name');
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return 'world';
        });
        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }
}
