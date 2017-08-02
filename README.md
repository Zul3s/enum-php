# PHP Enum

[![Build Status](https://travis-ci.org/Zul3s/enum-php.svg?branch=master)](https://travis-ci.org/Zul3s/enum-php)
[![Latest Stable Version](https://poser.pugx.org/zul3s/enum-php/v/stable)](https://packagist.org/packages/zul3s/enum-php)
[![Total Downloads](https://poser.pugx.org/zul3s/enum-php/downloads)](https://packagist.org/packages/zul3s/enum-php)
[![License](https://poser.pugx.org/zul3s/enum-php/license)](https://packagist.org/packages/zul3s/enum-php)
[![composer.lock](https://poser.pugx.org/zul3s/enum-php/composerlock)](https://packagist.org/packages/zul3s/enum-php)


PHP 7.1 only supported.  
It's an abstract class that needs to be extended to use it.

## What is an Enumeration?

[Wikipedia](http://wikipedia.org/wiki/Enumerated_type)
> In computer programming, an enumerated type (also called enumeration or enum)
> is a data type consisting of a set of named values called elements, members
> or enumerators of the type. The enumerator names are usually identifiers that
> behave as constants in the language. A variable that has been declared as
> having an enumerated type can be assigned any of the enumerators as a value.
> In other words, an enumerated type has values that are different from each
> other, and that can be compared and assigned, but which do not have any
> particular concrete representation in the computer's memory; compilers and
> interpreters can represent them arbitrarily.

### Some advantages

When you using an enum instead of class constants, you take advantages of 

- Type-hint : e.g. function setEnum(Enum $enum)
- Enrich your enum class with methods : e.g. first, format, ...
- Get all possibilities keys/values

## Why this ?

Actually you can find others package offer PHP enum implementation,
but this package have some advantage :

- Singleton : this package use singleton pattern
- Fast : Implemented small execution cache 
- Right way : No circular referential
- Small : this package not needed an other to use
- Quality : PSR-2 standard

## Installation

````
composer require zul3s/enum-php
````

## Declaration

````
use Zul3s\EnumPhp\Enum;

/**
 * Simpson enum
 *
 * @method static Simpson::HOMER()
 * @method static Simpson::MARGE()
 */
class Simpson extends Enum
{
    /**
    * @description('Description for Homer const')
    */
    const HOMER  = 1;
    const MARGE  = 'marjorie_jacqueline';
}
````
## Usage

````
$marge = Simpson::MARGE();

$homer = Simpson::byKey('HOMER');

$marge = Simpson::byValue('marjorie_jacqueline');

$homer = Simpson::byValue('1', false); // Disable strict type mode

````

#### Type-hint

````
function setMember(Simpson $member) 
{
    // ...
}
````

## Documentation 

##### Get 

- `$myEnum = MyEnumClass::ENUM_CONST()` Return enum search by const name (method name)
- `$myEnum = MyEnumClass::byValue(mixed $value, [optional] bool $strict)` Return enum search by value 
- `$myEnum = MyEnumClass::byKey(string $constName)` Return enum search by const name

##### Use 

- `$myEnum->getValue() : mixed` Return value of enum
- `$myEnum->getKey() : string` Return string with const name
- `$myEnum->getDescription() : string` Return description annotation if is set or exception
- `$myEnum->isEquals(Enum $myEnum) : bool` Check if enum is equals to another
- `echo $myEnum : string` __toString() have implemented to return cast of string value

##### Helper

- `MyEnumClass::getAll() : array` Return array with all MyEnum possibilities
- `MyEnumClass::getValues() : array` Return simple associate array with key is const name and value is const value
- `MyEnumClass::isValidKey(string $testedValue) : bool` Check if tested value is valid const name
- `MyEnumClass::isValidValue($testValue, [optional] bool $strict) : bool` Check if tested value is valid const value