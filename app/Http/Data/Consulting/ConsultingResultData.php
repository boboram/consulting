<?php

namespace App\Http\Data\Consulting;


use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Class ConsultingResultData
 * @package App\Http\Data\Consulting
 */
#[MapName(SnakeCaseMapper::class)]
class ConsultingResultData extends Data
{

    /**
     * ConsultingResultData constructor.
     * @param string $solutionType
     * @param int $count
     */
    public function __construct(
        public string $solutionType = "",
        public int $count = 0
    ) {

    }
}
