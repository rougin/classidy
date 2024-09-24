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
    public function test_all_features()
    {
        $expected = $this->find('WithAllFeatures');

        $class = $this->newClass('WithAllFeatures');

        $extends = 'Rougin\Classidy\Fixture\Classes\WithMethod';
        $class->extendsTo($extends);

        $interface = 'Rougin\Classidy\Fixture\Classable';
        $class->addInterface($interface);

        $class->addTrait('Rougin\Classidy\Fixture\Traitable');

        $method = new Method('greet');
        $method->addStringArgument('name')
            ->withDefaultValue('world');
        $method->setReturn('string');
        $method->setCodeEval(function ($name = 'world')
        {
            return 'Hello ' . $name . '!';
        });
        $class->addMethod($method);

        $method = new Method('sample');
        $method->setReturn('string');
        $method->setCodeLine(function ($lines)
        {
            $lines[] = '$text = \'text\';';
            $lines[] = '';
            $lines[] = 'return \'This is a sample \' . $text;';

            return $lines;
        });
        $class->addMethod($method);

        $actual = $this->make($class);

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
    public function test_class_with_extend_from_other_namespace()
    {
        $expected = $this->find('WithSeparateExtend');

        $class = $this->newClass('WithSeparateExtend');

        $class->extendsTo('Rougin\Classidy\Template');

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_class_with_extends()
    {
        $expected = $this->find('WithExtends');

        $class = $this->newClass('WithExtends');

        $parent = 'Rougin\Classidy\Fixture\Classes\WithMethod';
        $class->extendsTo($parent);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_class_with_interface()
    {
        $expected = $this->find('WithInterface');

        $class = $this->newClass('WithInterface');

        $interface = 'Rougin\Classidy\Fixture\Classable';
        $class->addInterface($interface);

        $actual = $this->make($class);

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

        $method = new Method('items');
        $method->addArrayArgument('items', 'string[]');
        $method->setReturn('string[]');
        $method->setCodeLine(function ($lines)
        {
            $lines[] = 'return $items;';

            return $lines;
        });
        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

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
    public function test_class_with_property()
    {
        $expected = $this->find('WithProperty');

        $class = $this->newClass('WithProperty');

        $default = array('property', 'argument', 'classidy');
        $class->addArrayProperty('cols', 'string[]')
            ->withDefaultValue($default);

        $class->addArrayProperty('types', 'integer[]')
            ->withLink('https://roug.in');

        $default = array(
            'page_query_string' => true,
            'use_page_numbers' => true,
            'query_string_segment' => 'p',
            'reuse_query_string' => true,
        );

        $class->addArrayProperty('items', 'array<string, mixed>')
            ->withDefaultValue($default);

        $class->addIntegerProperty('age', true)
            ->withComment('Age of the specified class.')
            ->asPublic();

        $class->addStringProperty('name')
            ->withDefaultValue('Classidy');

        $name = 'Rougin\Classidy\Fixture\Classes\WithMethod';
        $class->addClassProperty('with', $name);

        $texts = array('Shouts from the specified class.');
        $texts[] = 'If enabled, it will be on all upper cases.';
        $class->addBooleanProperty('loud')
            ->withComment($texts)
            ->asPrivate()
            ->withDefaultValue(false);

        $class->addFloatProperty('grade');

        $method = new Method('shout');
        $method->setReturn('boolean');
        $method->setCodeEval(function ()
        {
            return $this->loud;
        });
        $class->addMethod($method);

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_class_with_property_tag()
    {
        $expected = $this->find('WithPropertyTag');

        $class = $this->newClass('WithPropertyTag');

        $class->addIntegerProperty('age', true)
            ->asPublic()
            ->asTag();

        $class->addStringProperty('name')
            ->withDefaultValue('Classidy')
            ->asTag();

        $class->addBooleanProperty('loud')
            ->asPrivate()
            ->withDefaultValue(false)
            ->asTag();

        $class->addFloatProperty('grade')
            ->asTag();

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_class_with_trait()
    {
        $expected = $this->find('WithTrait');

        $class = $this->newClass('WithTrait');

        $class->extendsTo('Rougin\Classidy\Template');
        $class->addTrait('Rougin\Classidy\Fixture\Traitable');

        $actual = $this->make($class);

        $this->assertEquals($expected, $actual);
    }
}
