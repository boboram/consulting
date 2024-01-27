<?php

namespace Tests\Unit\Consulting;

use App\Http\Enums\SolutionSubType;
use App\Http\Enums\TagType;
use Tests\TestCase;

/**
 * Class SolutionSubTypeTest
 * @package Tests\Unit\Consulting
 */
class SolutionSubTypeTest extends TestCase
{
    /**
     * @test
     * @testdox enum cases 올바르게 획득하는지 확인
     * @return void
     */
    public function testGetEnumCases(): void
    {
        $this->assertEquals([
            SolutionSubType::IntermittentFasting->value,
            SolutionSubType::LCHF->value,
            SolutionSubType::Crossfit->value,
            SolutionSubType::CardioExercise->value,
            SolutionSubType::Strength->value,
            SolutionSubType::Spinning->value,
        ],
            SolutionSubType::getValues());
    }

    /**
     * @test
     * @testdox Crossfit 솔루션 타입에 대한 태그 값 올바르게 획득하는지 확인
     * @return void
     */
    public function testGetTagsWithCrossfit(): void
    {
        $this->assertEquals(
            [
                TagType::EnoughMoney->value,
                TagType::StrongWill->value
            ],
            SolutionSubType::getTags(SolutionSubType::Crossfit->value)
        );
    }

}

