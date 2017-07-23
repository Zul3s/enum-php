<?php

namespace Zul3s\Tests\Enum;

use Zul3s\EnumPhp\EnumCacheManagement;
use Zul3s\Tests;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumTest
 * @package Zul3s\Tests\Enum
 */
class EnumTest extends TestCase
{
    /**
     * Test getValue method
     */
    public function testGetValue() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertEquals(FakeEnum::VALUE_INT_1, $enum->getValue());
        $enum = FakeEnum::VALUE_INT_2();
        $this->assertEquals(FakeEnum::VALUE_INT_2, $enum->getValue());
        $enum = FakeEnum::VALUE_STRING_1();
        $this->assertEquals(FakeEnum::VALUE_STRING_1, $enum->getValue());
        $enum = FakeEnum::VALUE_STRING_2();
        $this->assertEquals(FakeEnum::VALUE_STRING_2, $enum->getValue());

        $this->assertNotEquals(FakeEnum::VALUE_INT_2, $enum->getValue());
    }

    /**
     * Test getKey method
     */
    public function testGetKey() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertEquals('VALUE_INT_1', $enum->getKey());
        $this->assertNotEquals('VALUE_INT_2', $enum->getKey());

        $enum = FakeEnum::VALUE_INT_2();
        $this->assertEquals('VALUE_INT_2', $enum->getKey());
        $this->assertNotEquals('VALUE_INT_1', $enum->getKey());

        $enum = FakeEnum::VALUE_STRING_1();
        $this->assertEquals('VALUE_STRING_1', $enum->getKey());
        $this->assertNotEquals('VALUE_STRING_2', $enum->getKey());

        $enum = FakeEnum::VALUE_STRING_2();
        $this->assertEquals('VALUE_STRING_2', $enum->getKey());
        $this->assertNotEquals('VALUE_INT_2', $enum->getKey());
    }

    /**
     * Test to string method
     */
    public function testToString() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertEquals('1', $enum->__toString());

        $enum = FakeEnum::VALUE_STRING_1();
        $this->assertEquals('value_1', $enum->__toString());
    }

    /**
     * Test isEquals method
     */
    public function testIsEquals() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertTrue($enum->isEquals(FakeEnum::VALUE_INT_1()));
        $this->assertNotTrue($enum->isEquals(FakeEnum::VALUE_INT_2()));
    }

    /**
     * Test byValue method
     */
    public function testByValue() : void
    {
        $enum = FakeEnum::byValue(FakeEnum::VALUE_INT_1);
        $this->assertTrue($enum->isEquals(FakeEnum::VALUE_INT_1()));
        $this->assertNotTrue($enum->isEquals(FakeEnum::VALUE_INT_2()));

        $enum = FakeEnum::byValue(FakeEnum::VALUE_STRING_1);
        $this->assertTrue($enum->isEquals(FakeEnum::VALUE_STRING_1()));

        $enum = FakeEnum::byValue('1', false);
        $this->assertTrue($enum->isEquals(FakeEnum::VALUE_INT_1()));

        $this->expectException(\UnexpectedValueException::class);
        FakeEnum::byValue('HomeS.');
        FakeEnum::byValue('1');
    }

    /**
     * Test byName method
     */
    public function testByKey() : void
    {
        $enum = FakeEnum::byKey('VALUE_INT_1');
        $this->assertTrue($enum->isEquals(FakeEnum::VALUE_INT_1()));
        $this->assertTrue($enum->getValue() === FakeEnum::VALUE_INT_1);

        $this->expectException(\UnexpectedValueException::class);
        FakeEnum::byKey('HomeS');
    }

    /**
     * Test getAll method
     */
    public function testGetAll() : void
    {
        $all = FakeEnum::getAll();
        $this->assertTrue(is_array($all));

        $reflection = new \ReflectionClass(FakeEnum::class);
        $const = $reflection->getConstants();

        $this->assertTrue(count($all) === count($const));
        for ($i = 0; $i < count($all); $i++) {
            $this->assertTrue($all[$i] instanceof FakeEnum);
            for ($y = ($i + 1); $y < count($all); $y++) {
                $this->assertNotTrue($all[$i]->isEquals($all[$y]));
            }
        }
    }

    /**
     * Test getValues method
     */
    public function testGetValues() : void
    {
        $values = FakeEnum::getValues();
        $this->assertTrue(is_array($values));

        $reflection = new \ReflectionClass(FakeEnum::class);
        $const = $reflection->getConstants();

        $this->assertTrue($values === $const);
    }

    /**
     * Test is valid key method
     */
    public function testIsValidKey() : void
    {
        $this->assertTrue(FakeEnum::isValidKey(FakeEnum::VALUE_INT_1()->getKey()));
        $this->assertTrue(FakeEnum::isValidKey(FakeEnum::VALUE_INT_2()->getKey()));
        $this->assertTrue(FakeEnum::isValidKey(FakeEnum::VALUE_STRING_1()->getKey()));
        $this->assertTrue(FakeEnum::isValidKey(FakeEnum::VALUE_STRING_2()->getKey()));

        $this->assertNotTrue(FakeEnum::isValidKey('HomeS'));
    }

    /**
     * Test is valid value method
     */
    public function testIsValidValue() : void
    {
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::VALUE_INT_1));
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::VALUE_INT_2));
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::VALUE_STRING_1));
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::VALUE_STRING_2));
        $this->assertTrue(FakeEnum::isValidValue('1', false));

        $this->assertNotTrue(FakeEnum::isValidValue('HomeS'));
        $this->assertNotTrue(FakeEnum::isValidValue('1'));
    }

    /**
     * Test cache
     */
    public function testCache() : void
    {
        FakeEnum::VALUE_INT_1();
        $reflection = new \ReflectionClass(EnumCacheManagement::class);
        $instanced = $reflection->getProperty('instanced');
        $instanced->setAccessible(true);
        $value = $instanced->getValue(EnumCacheManagement::class);

        $this->assertTrue(array_key_exists(FakeEnum::class, $value));
    }

    /**
     * Test singleton
     */
    public function testSingleton() : void
    {
        $enum_1 = FakeEnum::VALUE_INT_1();
        $enum_2 = FakeEnum::VALUE_INT_2();
        $enum_3 = FakeEnum::VALUE_INT_1();

        $this->assertTrue($enum_1 === $enum_3);
        $this->assertNotTrue($enum_1 === $enum_2);
    }
}
