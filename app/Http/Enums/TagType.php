<?php

namespace App\Http\Enums;


/**
 * Class TagType
 * @package App\Http\Enums
 */
enum TagType: string
{
    case EnoughTime = "enough_time";

    case StrongWill = "strong_will";

    case EnoughMoney = "enough_money";

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    //TODO 3 /2 / 1 이런식으로 태그 순서에 대한 가중치 주기 
    /**
     * @return int
     */
    public static function getTagCount(): int
    {
        return count(self::getValues());
    }
}
