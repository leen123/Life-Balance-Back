<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Days extends Enum
{
    const sunday = 1;
    const monday = 2;
    const tuesday = 3;
    const wednesday = 4;
    const thursday = 5;
    const friday = 6;
    const saturday = 7;
}
