# Classidy

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

A package that creates PHP classes using PHP. That's it.

## Installation

Install `Classify` through [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/classidy
```

## Basic Usage

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

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/classidy/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/classidy?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/classidy.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/classidy.svg?style=flat-square

[link-build]: https://github.com/rougin/classidy/actions
[link-changelog]: https://github.com/rougin/classidy/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/classidy/contributors
[link-coverage]: https://app.codecov.io/gh/rougin/classidy
[link-downloads]: https://packagist.org/packages/rougin/classidy
[link-license]: https://github.com/rougin/classidy/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/classidy
[link-upgrading]: https://github.com/rougin/classidy/blob/master/UPGRADING.md