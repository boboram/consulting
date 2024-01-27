<?php

namespace Tests\Unit\Consulting;

use App\Http\Enums\SolutionSubType;
use App\Http\Enums\SolutionType;
use App\Http\Enums\TagType;
use App\Http\Services\DietExpert;
use App\Http\Services\FitnessCoach;
use App\Http\Services\PersonalTrainingInterface;
use Tests\TestCase;

/**
 * Class PersonalTrainingInterfaceTest
 * @package Tests\Unit\Consulting
 */
class PersonalTrainingInterfaceTest extends TestCase
{

    /**
     * @test
     * @testdox DietExpert class 인 경우 원하는 함수가 출력되는지 확인
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testSetSolutionSubTypesWithDietExpertClass(): void
    {
        $class = $this->factory(SolutionType::Diet->value);
        $solutionTypes = $class->getSolutionSubTypes();

        $this->assertEquals([
            SolutionSubType::IntermittentFasting->value,
            SolutionSubType::LCHF->value
        ],
            $solutionTypes);
    }

    /**
     * @test
     * @testdox FitnessCoach class 인 경우 원하는 함수가 출력되는지 확인
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testSetSolutionSubTypesWithFitnessCoachClass(): void
    {
        $class = $this->factory(SolutionType::Diet->value);
        $solutionTypes = $class->getSolutionSubTypes();

        $this->assertEquals([
            SolutionSubType::Crossfit->value,
            SolutionSubType::CardioExercise->value,
            SolutionSubType::Strength->value,
            SolutionSubType::Spinning->value,
        ],
            $solutionTypes);
    }

    /**
     * 솔루션 타입에 맞는 클래스 획득 함수
     * @param string $solutionType
     * @return \App\Http\Services\PersonalTrainingInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function factory(string $solutionType): PersonalTrainingInterface
    {
        return app()->make(
            match ($solutionType) {
                SolutionType::Diet->value => DietExpert::class,
                default => FitnessCoach::class
            }
        );
    }

    /**
     * @test
     * @testdox
     * @return void
     */
    public function testGetPriorityCnt(): void
    {
        $priorityCnt = $this->getPriorityCnt(
            SolutionSubType::Crossfit->value,
            [TagType::EnoughMoney->value]
        );

        $this->assertEquals(3, $priorityCnt);
    }

    /**
     * @test
     * @testdox
     * @return void
     */
    public function testGetPriorityCntWithNotMatchingTagData(): void
    {
        $priorityCnt = $this->getPriorityCnt(
            SolutionSubType::IntermittentFasting->value,
            [TagType::EnoughMoney->value]
        );

        $this->assertEquals(0, $priorityCnt);
    }

    /**
     * @test
     * @testdox
     * @return void
     */
    public function testGetPriorityCntWithMatchingData(): void
    {
        $priorityCnt = $this->getPriorityCnt(
            SolutionSubType::Crossfit->value,
            [TagType::EnoughMoney->value, TagType::StrongWill->value]
        );

        $this->assertEquals(8, $priorityCnt);
    }

    /**
     * 솔루션 하위 태그들과 선호 타입들을 비교하며 가중치를 획득하는 함수
     * @param string $solutionSubType
     * @param array $tags
     * @return int
     */
    private function getPriorityCnt(string $solutionSubType, array $tags): int
    {
        $solutionTagCnt = TagType::getTagCount();
        $tagCnt = count($tags);
        $priorityCnt = 0;

        foreach ($tags as $tag) {
            $tagIdx = array_search(
                $tag,
                SolutionSubType::getTags($solutionSubType)
            );

            if (is_numeric($tagIdx)) {
                $priorityCnt += (($solutionTagCnt - $tagIdx) * $tagCnt);
            }
            $tagCnt--;
        }

        return $priorityCnt;
    }
}

