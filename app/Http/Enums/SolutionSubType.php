<?php

namespace App\Http\Enums;


/**
 * Class SolutionSubType
 * @package App\Http\Enums
 */
enum SolutionSubType: string
{
    case IntermittentFasting = "Intermittent Fasting";

    case LCHF = "LCHF";

    case Crossfit = "Crossfit";

    case CardioExercise = "Cardio Exercise";

    case Strength = "Strength";

    case Spinning = "Spinning";

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array[]
     */
    public static function getTags(string $solutionSubType): array
    {
        return match ($solutionSubType) {
            self::IntermittentFasting->value => [
                TagType::EnoughTime->value,
                TagType::StrongWill->value
            ],
            self::LCHF->value, self::Spinning->value => [
                TagType::EnoughMoney->value
            ],
            self::Crossfit->value => [
                TagType::EnoughMoney->value,
                TagType::StrongWill->value
            ],
            self::CardioExercise->value => [
                TagType::StrongWill->value
            ],
            self::Strength->value => [
                TagType::StrongWill->value,
                TagType::EnoughTime->value
            ],
            default => []
        };
    }
}
