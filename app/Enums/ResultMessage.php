<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ResultMessage extends Enum
{
    const CORRECT_ANSWER = 'Corrected answers 😁😁';
    const IN_CORRECT_ANSWER = 'Incorrect answers 😢😢';
}
