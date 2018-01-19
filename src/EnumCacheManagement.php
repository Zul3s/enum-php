<?php


namespace Zul3s\EnumPhp;

use Zul3s\EnumPhp\Interfaces\EnumInterface;

/**
 * Class EnumCacheManagement
 *
 * @package Zul3s\EnumPhp
 *
 * @author Julien Zirnheld <julienzirnheld@gmail.com>
 */
abstract class EnumCacheManagement
{
    /**
     * @var array
     *
     * $_instanced[
     *  'className' = [         array with enum class name in key
     *      'const Name' = [    array with const value in key
     *          Enum = object   Enum instance of class with specific values
     *      ],
     *      'const Name' = [...],
     *  ],
     *  'className' = [...],
     * ]
     */
    private static $instanced = array();

    /**
     * Used to init Enum called class cache
     *
     * @param $className
     * @throws \ReflectionException
     */
    private static function start($className) : void
    {
        if (!self::isInstanced($className)) {
            self::instancedCurrentClass($className);
        }
    }

    /**
     * Check if enum class has already instanced
     * @param string $className
     * @return bool
     */
    public static function isInstanced(string $className) : bool
    {
        return array_key_exists($className, self::$instanced);
    }

    /**
     * @param string $className
     * @return void
     * @throws \ReflectionException
     */
    public static function instancedCurrentClass(string $className) : void
    {
        $reflection = new \ReflectionClass($className);

        self::$instanced[$className] = array();
        foreach ($reflection->getConstants() as $key => $value) {
            $class = $reflection->newInstanceWithoutConstructor();
            $constructor = $reflection->getConstructor();
            $constructor->setAccessible(true);
            $constructor->invokeArgs($class, [$value, $key]);
            self::$instanced[$className][$key] = $class;
        }
    }

    /**
     * @param string $className
     *
     * @return void
     * @throws \ReflectionException
     */
    public static function setDescriptions(string $className) : void
    {
        $annotations = ConstAnnotationsParser::parseAndReturnAnnotations($className);

        foreach ($annotations as $key => $annotation) {
            if (array_key_exists('description', $annotation)) {
                self::setDescription(self::$instanced[$className][$key], $annotation['description']);
            }
        }
    }

    /**
     * @param Enum $class
     * @param string $value
     *
     * @return void
     * @throws \ReflectionException
     */
    private static function setDescription(Enum $class, string $value) : void
    {
        $reflection = new \ReflectionClass(get_class($class));
        $property = $reflection->getProperty('description');
        $property->setAccessible(true);
        $property->setValue($class, $value);
    }

    /**
     * @param $className
     * @param $constName
     * @throws \UnexpectedValueException
     * @return EnumInterface
     * @throws \ReflectionException
     */
    public static function getInstanceByName(string $className, string $constName) : EnumInterface
    {
        self::start($className);
        if (!array_key_exists($constName, self::$instanced[$className])) {
            throw new \UnexpectedValueException("Name '$constName' is not part of the enum " . $className);
        }
        return self::$instanced[$className][$constName];
    }

    /**
     * @param string $className
     * @param $constValue
     * @param bool $strict
     * @throws \UnexpectedValueException
     * @return EnumInterface
     * @throws \ReflectionException
     */
    public static function getInstanceByValue(string $className, $constValue, bool $strict) : EnumInterface
    {
        self::start($className);
        /** @var Enum $class */
        foreach (self::$instanced[$className] as $class) {
            if ($strict) {
                if ($class->getValue() === $constValue) {
                    return $class;
                }
            } else {
                if ($class->getValue() == $constValue) {
                    return $class;
                }
            }
        }
        throw new \UnexpectedValueException("Value '$constValue' is not part of the enum " . $className);
    }

    /**
     * Check if $constName is a const name into the called Enum class
     *
     * @param string $className
     * @param string $constName
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidKey(string $className, string $constName) : bool
    {
        self::start($className);
        /** @var Enum $class */
        foreach (self::$instanced[$className] as $class) {
            if ($class->getKey() === $constName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if $constValue is a const value into the called Enum class
     *
     * @param string $className
     * @param string $constValue
     * @param bool $strict
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValidValue(string $className, $constValue, bool $strict) : bool
    {
        self::start($className);
        /** @var Enum $class */
        foreach (self::$instanced[$className] as $class) {
            if ($strict) {
                if ($class->getValue() === $constValue) {
                    return true;
                }
            } else {
                if ($class->getValue() == $constValue) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get all possible Enums class instance
     *
     * @param $className
     * @return array
     * @throws \ReflectionException
     */
    public static function getAll($className) : array
    {
        self::start($className);

        $all = array();
        /** @var Enum $class */
        foreach (self::$instanced[$className] as $class) {
            $all[] = $class;
        }
        return $all;
    }

    /**
     * Get all values with keys for called class Enum
     *
     * @param $className
     * @return array
     * @throws \ReflectionException
     */
    public static function getValues($className) : array
    {
        self::start($className);

        $values = array();
        /** @var Enum $class */
        foreach (self::$instanced[$className] as $class) {
            $values[$class->getKey()] = $class->getValue();
        }
        return $values;
    }
}
