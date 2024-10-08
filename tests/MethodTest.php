<?php

namespace Rougin\Classidy;

/**
 * @package Classidy
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MethodTest extends Testcase
{
    /**
     * @return void
     */
    public function test_code_as_lines()
    {
        $expected = $this->find('WithComments');

        $class = $this->newClass('WithComments');

        $method = new Method('hello');
        $method->setReturn('string');
        $method->setCodeLine(function ($lines)
        {
            $lines[] = 'return \'Hello world!\';';

            return $lines;
        });

        $texts = array('Returns the text "Hello world!".');
        $texts[] = 'This should be another comment text.';
        $method->setComment($texts);

        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_as_private()
    {
        $expected = $this->find('WithPrivateMethod');

        $class = $this->newClass('WithPrivateMethod');

        $method = new Method('hello');
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return $this->greet();
        });
        $class->addMethod($method);

        $method = new Method('greet');
        $method->asPrivate();
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return 'Hello world!';
        });
        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_as_protected()
    {
        $expected = $this->find('WithProtectedMethod');

        $class = $this->newClass('WithProtectedMethod');

        $method = new Method('hello');
        $method->asProtected();
        $method->setReturn('string');
        $method->setCodeEval(function ()
        {
            return 'Hello world!';
        });

        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_as_tag()
    {
        $expected = $this->find('WithMethodAsTag');

        $class = $this->newClass('WithMethodAsTag');

        $class->addStringProperty('name')->asTag();

        $method = new Method('rating');
        $method->setReturn('string');

        $method->addFloatArgument('grade')
            ->asTag();

        $method->addBooleanArgument('passer')
            ->withDefaultValue(true)
            ->asTag();

        $class->addMethod($method);

        $actual = $this->make($class);

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

        $class->addMethod($method);

        $actual = $this->make($class);

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

        $class->addMethod($method);

        $actual = $this->make($class);

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

        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_method_with_link()
    {
        $expected = $this->find('WithLink');

        $class = $this->newClass('WithLink');

        $method = new Method('hello');
        $method->setLink('https://roug.in');
        $method->setReturn('string');

        $method->setCodeEval(function ()
        {
            return 'Hello world!';
        });

        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }
}
