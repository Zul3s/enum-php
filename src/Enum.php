<?php

namespace Zul3s\EnumPhp;

use JsonSerializable;
use Zul3s\EnumPhp\Interfaces\EnumInterface;

/**
 * Class Enum
 * Advanced Php Enum Type
 *
 * @package Zul3s\EnumPhp
 *
 * @author Julien Zirnheld <julienzirnheld@gmail.com>
 */
abstract class Enum implements EnumInterface, JsonSerializable
{
    /**
     * @var mixed $value
     */
    private $value;

    /**
     * @var string $key
     */
    private $key;

    /**
     * Enum constructor.
     * @param $value
     * @param string $key
     */
    final private function __construct($value, string $key)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @param $method
     * @param array $args
     * @return EnumInterface
     */
    final public static function __callStatic($method, array $args) : EnumInterface
    {
        return self::byKey($method);
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        return (string) $this->getValue();
    }

    /**
     * @throws \LogicException
     */
    final private function __clone()
    {
        throw new \LogicException('Enums are singleton, it is not cloneable');
    }

    /**
     * Return the enum value when json_encode() is called
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

    /**
     * Return enum value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return enum name
     *
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }


    /**
     * Check if this instance of enum is equals to another
     *
     * @param EnumInterface $enum
     * @return bool
     */
    public function isEquals(EnumInterface $enum) : bool
    {
        return $this === $enum;
    }

    /**
     * Return instance of called class enum with const name
     *
     * @param $key
     * @return EnumInterface
     */
    public static function byKey(string $key) : EnumInterface
    {
        return EnumCacheManagement::getInstanceByName(get_called_class(), $key);
    }

    /**
     * This method can be used when you have just value of const in Enum class
     * and you want the Enum instance.
     * The optional strict parameter is used to be sure Enum class has an instance
     * with same value and same type of value
     *
     * @param $value
     * @param bool $strict compare the type to be sure
     * @return EnumInterface
     */
    public static function byValue($value, bool $strict = true) : EnumInterface
    {
        return EnumCacheManagement::getInstanceByValue(get_called_class(), $value, $strict);
    }

    /**
     * Get all possible instance of called class Enum
     *
     * @return array [Enum {...}, Enum {...}, ...]
     */
    public static function getAll() : array
    {
        return EnumCacheManagement::getAll(get_called_class());
    }

    /**
     * Get values of called class Enum
     *
     * @return array [key => value, key => value, ...]
     */
    public static function getValues() : array
    {
        return EnumCacheManagement::getValues(get_called_class());
    }
    
    /**
     * Check if key is valid into the class
     *
     * @param $key
     * @return boolean
     */
    public static function isValidKey(string $key) : bool
    {
        return EnumCacheManagement::isValidKey(get_called_class(), $key);
    }

    /**
     * Check if value is valid into the class
     * The optional strict parameter is used for strict compares
     *
     * @param mixed $value
     * @param bool $strict
     * @return boolean
     */
    public static function isValidValue($value, bool $strict = true) : bool
    {
        return EnumCacheManagement::isValidValue(get_called_class(), $value, $strict);
    }
}
