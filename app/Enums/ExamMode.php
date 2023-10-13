<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ExamMode extends Enum
{
    const TIMED_MODE = 1;
    const REVIEW_MODE = 2;
}
