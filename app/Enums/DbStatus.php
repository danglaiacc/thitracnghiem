<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DbStatus extends Enum
{
    const NO_CHANGE = 0;
    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;
}
