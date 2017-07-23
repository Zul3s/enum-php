<?php

namespace Zul3s\Tests\Enum;

use Zul3s\EnumPhp\Enum;

/**
 * Class EnumTest
 *
 * @package Zul3s\Tests\Enum
 *
 * @author Julien Zirnheld <julienzirnheld@gmail.com>
 * @category
 *
 * @method static FakeEnum VALUE_INT_1()
 * @method static FakeEnum VALUE_INT_2()
 * @method static FakeEnum VALUE_STRING_1()
 * @method static FakeEnum VALUE_STRING_2()
 */
class FakeEnum extends Enum
{
    const VALUE_INT_1 = 1;
    const VALUE_INT_2 = 2;
    const VALUE_STRING_1 = 'value_1';
    const VALUE_STRING_2 = "value 2";
}
