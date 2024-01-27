<?php

namespace App\Http\Services;

use App\Http\Enums\SolutionSubType;

/**
 * Class DietExpert - DIET 담당
 * @package App\Http\Services
 */
class DietExpert extends PersonalTrainingInterface
{

    /**
     * @return array
     */
    public function getSolutionSubTypes(): array
    {
        return $this->solutionTypes = [
            SolutionSubType::IntermittentFasting->value,
            SolutionSubType::LCHF->value
        ];
    }
}
