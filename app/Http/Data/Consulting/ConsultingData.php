<?php

namespace App\Http\Data\Consulting;


use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Class ConsultingData
 * @package App\Http\Data\Consulting
 */
#[MapName(SnakeCaseMapper::class)]
class ConsultingData extends Data
{

    /**
     * ConsultingData constructor.
     * @param array $solutionTypes
     * @param array $tags
     */
    public function __construct(
        public array $solutionTypes = [],
        public array $tags = []
    ) {

    }
}
