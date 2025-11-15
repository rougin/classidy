# Classidy

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

A package that creates PHP classes using PHP. That's it.

## Installation

Install `Classidy` through [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/classidy
```

## Basic usage

### Creating a simple class

Creating a PHP class only requires the `Classidy` and `Generator` classes:

``` php
// index.php

use Rougin\Classidy\Classidy;
use Rougin\Classidy\Generator;
use Rougin\Classidy\Method;

// ...

// Create a new class definition ---
$class = new Classidy;
// ---------------------------------

// Define the details of the class ------------
$class->setComment('Sample class for Acme.');
$class->setNamespace('Acme');
$class->setPackage('Acme');
$class->setAuthor('John Doe', 'jdoe@acme.com');
$class->setName('Greet');
// --------------------------------------------

// Add a "greet" method in the class ---
$method = new Method('greet');
$method->setCodeEval(function ()
{
    return 'Hello world!';
});
$class->addMethod($method);
// -------------------------------------

// Generate the class --------
$generator = new Generator;

echo $generator->make($class);
// ---------------------------
```

``` bash
$ php index.php

<?php

namespace Acme;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet
{
    public function greet()
    {
        return 'Hello world!';
    }
}
```

The `setCodeLine` method can also be used for specifying code of a method in a line based format. This may be useful in adding conditions in generating code of a method:

``` php
// index.php

// ...

$shout = false;

$method->setCodeLine(function ($lines) use ($shout)
{
    if ($shout)
    {
        $lines[] = "return 'HELLO WORLD!';";
    }
    else
    {
        $lines[] = "return 'Hello world!';";
    }

    return $lines;
});

// ...
```

### Adding parent class, interfaces

The class can be added with a parent class using `extendsTo`:

``` php
// index.php

use Acme\Hello\Greeter;

// ...

// Define the details of the class ---
// ...

$class->extendsTo(Greeter::class);

// ...
// -----------------------------------

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter
{
    public function greet()
    {
        return 'Hello world!';
    }
}
```

> [!NOTE]
> If the added parent class or interface is not from the same namespace of the class to be generated, `Classidy` will automatically import the said parent class/interface.

For adding interfaces, the `addInterface` method can be used:

``` php
// index.php

use Acme\Greetable;
use Acme\Helloable;

// ...

// Define the details of the class ----
// ...

$class->addInterface(Greetable::class);
$class->addInterface(Helloable::class);

// ...
// ------------------------------------

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
    public function greet()
    {
        return 'Hello world!';
    }
}
```

### Adding traits

Similar in defining class and interfaces, adding a trait is possible using `addTrait`:

``` php
// index.php

use Acme\Hello\Traitable;

// ...

// Define the details of the class ---
// ...

$class->addTrait(Traitable::class);

// ...
// -----------------------------------

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Traitable;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet
{
    use Traitable;

    public function greet()
    {
        return 'Hello world!';
    }
}
```

### Adding methods

Based from the first example, the `addMethod` can be used to add a method to the class:

``` php
// index.php

// ...

// Add a "greet" method in the class ---
$method = new Method('greet');
$method->setCodeEval(function ()
{
    return 'Hello world!';
});
$class->addMethod($method);
// -------------------------------------

// ...
```

To add arguments in a specified method, kindy use the following methods below:

| Method               | Description                                          |
|----------------------|------------------------------------------------------|
| `addArrayArgument`   | Adds a property with a `array` as its data type.     |
| `addBooleanArgument` | Adds an argument with a `boolean` as its data type.  |
| `addClassArgument`   | Adds an argument with the specified class.           |
| `addFloatArgument`   | Adds an argument with a `float` as its data type.    |
| `addIntegerArgument` | Adds an argument with an `integer` as its data type. |
| `addStringArgument`  | Adds an argument with a `string` as its data type.   |

``` php
// index.php

// ...

$method = new Method('greet');
$method->addBooleanArgument('shout')
    ->withDefaultValue(false);
$method->setReturn('string');
$method->setCodeEval(function ()
{
    return 'Hello world!';
});
$class->addMethod($method);

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
    /**
     * @param boolean $shout
     *
     * @return string
     */
    public function greet($shout = false)
    {
        return 'Hello world!';
    }
}
```

To add a class argument without being its type declared, add `withoutTypeDeclared` after `addClassArgument`:

``` php
// index.php

// ...

$method = new Method('greet');
$method->addClassArgument('test', 'Acme\Test')
    ->withoutTypeDeclared();
$method->setReturn('string');
$method->setCodeEval(function ($test)
{
    return $test->hello();
});
$class->addMethod($method);

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
    /**
     * @param \Acme\Test $test
     *
     * @return string
     */
    public function greet($test)
    {
        return $test->hello();
    }
}
```

A method can also be defined as `protected` or `private`:

``` php
// index.php

// ...

$method = new Method('greet');

// Set the method as "protected" ---
$method->asProtected();
// ---------------------------------

// Set the method as "private" ---
$method->asPrivate();
// -------------------------------

// ...
```

> [!NOTE]
> By default, all of the specified methods are in `public` visibility.

The method can be alternatively be specified as a `@method` tag in the class:

``` php
// index.php

// ...

$method = new Method('greet');

// ...

// Set as "@method" in the class ---
$method->asTag();
// ---------------------------------

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @method string greet(boolean $shout = false)
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
}
```

### Adding properties

Similiar to adding arguments in a method, adding properties to a class can be done by the following:

| Method               | Description                                         |
|----------------------|-----------------------------------------------------|
| `addArrayProperty`   | Adds a property with a `array` as its data type.    |
| `addBooleanProperty` | Adds a property with a `boolean` as its data type.  |
| `addClassProperty`   | Adds a property with the specified class.           |
| `addFloatProperty`   | Adds a property with a `float` as its data type.    |
| `addIntegerProperty` | Adds a property with an `integer` as its data type. |
| `addStringProperty`  | Adds a property with a `string` as its data type.   |

``` php
// index.php

// ...

$class->addStringProperty('text')
    ->withDefaultValue('Hello world!');

// ...

// Modify "greet" method to access "text" property ---
$method->setCodeEval(function ()
{
    return $this->text;
});
// ---------------------------------------------------

// ...
```

``` php
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
    /**
     * @var string
     */
    protected $text = 'Hello world!';

    /**
     * @param boolean $shout
     *
     * @return string
     */
    public function greet($shout = false)
    {
        return $this->text;
    }
}
```

To change a visibility of a property, the methods `asPublic` and `asPrivate` can be used:

``` php
// index.php

// ...

$class->addStringProperty('text')
    ->asPrivate()
    ->withDefaultValue('Hello world!');

// ...
```

> [!NOTE]
> By default, all of the specified properties are in `protected` visibility.

Alternatively, the property be specified as a `@property` tag in the class:

``` php
// index.php

// ...

$class->addStringProperty('text')->asTag();

// ...
```

``` bash
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * @property string $text
 *
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
}
```

### Setting an empty class

The `setEmpty` method can be used to clear any methods and properties previously specified. This maybe useful when generating empty classes without specifying a new `Classidy` class:

``` php
// index.php

// ...

$class->setEmpty();

// ...
```

``` php
$ php index.php

<?php

namespace Acme;

use Acme\Hello\Greeter;

/**
 * Sample class for Acme.
 *
 * @package Acme
 *
 * @author John Doe <jdoe@acme.com>
 */
class Greet extends Greeter implements Greetable, Helloable
{
}
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Contributing

See [CONTRIBUTING][link-contributing] on how to contribute.

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/classidy/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/classidy?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/classidy.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/classidy.svg?style=flat-square

[link-build]: https://github.com/rougin/classidy/actions
[link-changelog]: https://github.com/rougin/classidy/blob/master/CHANGELOG.md
[link-contributing]: https://github.com/rougin/classidy/blob/master/CONTRIBUTING.md
[link-coverage]: https://app.codecov.io/gh/rougin/classidy
[link-downloads]: https://packagist.org/packages/rougin/classidy
[link-license]: https://github.com/rougin/classidy/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/classidy
