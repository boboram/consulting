<?php

namespace App\Http\Enums;


/**
 * Class SolutionType
 * @package App\Http\Enums
 */
enum SolutionType: string
{
    case Diet = "DIET";

    case Fitness = "FITNESS";

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
