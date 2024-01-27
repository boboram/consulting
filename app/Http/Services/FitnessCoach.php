<?php

namespace App\Http\Services;

use App\Http\Enums\SolutionSubType;

/**
 * Class FitnessCoach - Fitness 담당
 * @package App\Http\Services
 */
class FitnessCoach extends PersonalTrainingInterface
{

    /**
     * @return array
     */
    public function getSolutionSubTypes(): array
    {
        return $this->solutionTypes = [
            SolutionSubType::Crossfit->value,
            SolutionSubType::CardioExercise->value,
            SolutionSubType::Strength->value,
            SolutionSubType::Spinning->value,
        ];
    }
}
