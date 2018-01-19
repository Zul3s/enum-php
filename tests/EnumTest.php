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
     * Test getDescription
     * @throws \ReflectionException
     */
    public function testGetDescription() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertEquals('Value int 1 description', $enum->getDescription());

        $enum = FakeEnum::VALUE_INT_2();
        $this->assertEquals('Value int 2 description', $enum->getDescription());

        $this->expectException(\InvalidArgumentException::class);
        FakeEnum::VALUE_STRING_1()->getDescription();
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
     * Test isEqual method
     */
    public function testIsEqual() : void
    {
        $enum = FakeEnum::VALUE_INT_1();
        $this->assertTrue($enum->isEqual(FakeEnum::VALUE_INT_1()));
        $this->assertNotTrue($enum->isEqual(FakeEnum::VALUE_INT_2()));
    }

    /**
     * Test byValue method
     * @throws \ReflectionException
     */
    public function testByValue() : void
    {
        $enum = FakeEnum::byValue(FakeEnum::VALUE_INT_1);
        $this->assertTrue($enum->isEqual(FakeEnum::VALUE_INT_1()));
        $this->assertNotTrue($enum->isEqual(FakeEnum::VALUE_INT_2()));

        $enum = FakeEnum::byValue(FakeEnum::VALUE_STRING_1);
        $this->assertTrue($enum->isEqual(FakeEnum::VALUE_STRING_1()));

        $enum = FakeEnum::byValue('1', false);
        $this->assertTrue($enum->isEqual(FakeEnum::VALUE_INT_1()));

        $this->expectException(\UnexpectedValueException::class);
        FakeEnum::byValue('HomeS.');
        FakeEnum::byValue('1');
    }

    /**
     * Test byName method
     * @throws \ReflectionException
     */
    public function testByKey() : void
    {
        $enum = FakeEnum::byKey('VALUE_INT_1');
        $this->assertTrue($enum->isEqual(FakeEnum::VALUE_INT_1()));
        $this->assertSame($enum->getValue(), FakeEnum::VALUE_INT_1);

        $this->expectException(\UnexpectedValueException::class);
        FakeEnum::byKey('HomeS');
    }

    /**
     * Test getAll method
     * @throws \ReflectionException
     */
    public function testGetAll() : void
    {
        $all = FakeEnum::getAll();
        $this->assertTrue(\is_array($all));

        $reflection = new \ReflectionClass(FakeEnum::class);
        $const = $reflection->getConstants();

        $this->assertSame(count($all), count($const));
        foreach ($all as $i => $iValue) {
            $this->assertInstanceOf(FakeEnum::class, $all[$i]);
            for ($y = ($i + 1), $yMax = count($all); $y < $yMax; $y++) {
                /** @var FakeEnum $iValue */
                $this->assertNotTrue($iValue->isEqual($all[$y]));
            }
        }
    }

    /**
     * Test getValues method
     * @throws \ReflectionException
     */
    public function testGetValues() : void
    {
        $values = FakeEnum::getValues();
        $this->assertTrue(\is_array($values));

        $reflection = new \ReflectionClass(FakeEnum::class);
        $const = $reflection->getConstants();

        $this->assertSame($values, $const);
    }

    /**
     * Test is valid key method
     * @throws \ReflectionException
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
     * @throws \ReflectionException
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
     * @throws \ReflectionException
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

        $this->assertSame($enum_1, $enum_3);
        $this->assertTrue($enum_1 === $enum_3);
        $this->assertNotSame($enum_1, $enum_2);
    }
}
