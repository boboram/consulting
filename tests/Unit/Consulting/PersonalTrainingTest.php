<?php

namespace Tests\Unit\Consulting;

use App\Http\Data\Consulting\ConsultingData;
use App\Http\Data\Consulting\ConsultingResultData;
use App\Http\Enums\SolutionSubType;
use App\Http\Enums\SolutionType;
use App\Http\Enums\TagType;
use App\Http\Services\DietExpert;
use App\Http\Services\FitnessCoach;
use App\Http\Services\PersonalTrainingInterface;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Class PersonalTrainingTest
 * @package Tests\Unit\Consulting
 */
class PersonalTrainingTest extends TestCase
{
    /**
     * @var \App\Http\Data\Consulting\ConsultingData
     */
    private ConsultingData $data;

    /**
     * @var array
     */
    private array $resultDatas = [];

    /**
     * @test
     * @testdox solution_types 값이 없는 경우 전채 데이터를 설정하도록 처리한다.
     * @return void
     */
    public function testInitSolutionTypeWithEmptySolutionTypes(): void
    {
        $this->data = new ConsultingData();

        $this->initSolutionType();

        $this->assertEquals(
            SolutionType::getValues(),
            $this->data->solutionTypes
        );
    }

    /**
     * @test
     * @testdox solution_types 에 하나의 값이라도 있다면 그 값 그대로 두기
     * @return void
     */
    public function testInitSolutionTypeWithFitnessSolutionType(): void
    {
        $this->data = new ConsultingData(
            solutionTypes: [SolutionType::Fitness->value]
        );

        $this->initSolutionType();

        $this->assertEquals([SolutionType::Fitness->value],
            $this->data->solutionTypes);
    }

    /**
     * 고객이 선호하는 솔루션 타입이 없다면 두개 모두 검사하도록 한다.
     * @return void
     */
    private function initSolutionType(): void
    {
        if (count($this->data->solutionTypes) === 0) {
            $this->data->solutionTypes = SolutionType::getValues();
        }
    }

    //TODO
    public function testSearchSolutionSubTypes(): void
    {
        $expectedData = [
            [
                'expect'         => SolutionSubType::IntermittentFasting->value,
                'expect_count'   => 1,
                'tags'           => [TagType::EnoughTime->value],
                'solution_types' => [SolutionType::Diet->value]
            ],
            [
                'expect'         => SolutionSubType::IntermittentFasting->value,
                'expect_count'   => 4,
                'tags'           => [TagType::StrongWill->value],
                'solution_types' => []
            ],
            [
                'expect'         => SolutionSubType::LCHF->value,
                'expect_count'   => 1,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => [SolutionType::Diet->value]
            ],
            [
                'expect'         => SolutionSubType::LCHF->value,
                'expect_count'   => 3,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => []
            ],
            [
                'expect'         => SolutionSubType::Crossfit->value,
                'expect_count'   => 2,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => SolutionSubType::Crossfit->value,
                'expect_count'   => 4,
                'tags'           => [
                    TagType::EnoughMoney->value,
                    TagType::StrongWill->value
                ],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => SolutionSubType::CardioExercise->value,
                'expect_count'   => 3,
                'tags'           => [TagType::StrongWill->value],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => SolutionSubType::Strength->value,
                'expect_count'   => 3,
                'tags'           => [
                    TagType::StrongWill->value,
                    TagType::EnoughTime->value
                ],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => SolutionSubType::Strength->value,
                'expect_count'   => 3,
                'tags'           => [
                    TagType::StrongWill->value,
                    TagType::EnoughTime->value
                ],
                'solution_types' => [SolutionType::Fitness->value]
            ],
        ];

        foreach ($expectedData as $data) {
            $this->data = new ConsultingData(
                solutionTypes: $data['solution_types'], tags: $data['tags']
            );

            $this->resultDatas = [];
            $this->initSolutionType();
            $this->searchSolutionSubTypes();

            $this->assertEquals(
                $data['expect_count'],
                count($this->resultDatas)
            );
        }

        $this->assertTrue(true);
    }

    public function testGetMatchingSolution(): void
    {
        $expectedData = [
            [
                'expect'         => [SolutionSubType::IntermittentFasting->value],
                'expect_count'   => 1,
                'tags'           => [TagType::EnoughTime->value],
                'solution_types' => [SolutionType::Diet->value]
            ],
            [
                'expect'         => [
                    SolutionSubType::CardioExercise->value,
                    SolutionSubType::Strength->value,
                    SolutionSubType::Crossfit->value,
                    SolutionSubType::IntermittentFasting->value
                ],
                'expect_count'   => 4,
                'tags'           => [TagType::StrongWill->value],
                'solution_types' => []
            ],
            [
                'expect'         => [SolutionSubType::LCHF->value],
                'expect_count'   => 1,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => [SolutionType::Diet->value]
            ],
            [
                'expect'         => [
                    SolutionSubType::Crossfit->value,
                    SolutionSubType::Spinning->value,
                    SolutionSubType::LCHF->value
                ],
                'expect_count'   => 3,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => []
            ],
            [
                'expect'         => [
                    SolutionSubType::Crossfit->value,
                    SolutionSubType::Spinning->value
                ],
                'expect_count'   => 2,
                'tags'           => [TagType::EnoughMoney->value],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => [
                    SolutionSubType::Crossfit->value,
                    SolutionSubType::Spinning->value,
                    SolutionSubType::CardioExercise->value,
                    SolutionSubType::Strength->value,
                ],
                'expect_count'   => 4,
                'tags'           => [
                    TagType::EnoughMoney->value,
                    TagType::StrongWill->value
                ],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => [
                    SolutionSubType::CardioExercise->value,
                    SolutionSubType::Strength->value,
                    SolutionSubType::Crossfit->value
                ],
                'expect_count'   => 3,
                'tags'           => [TagType::StrongWill->value],
                'solution_types' => [SolutionType::Fitness->value]
            ],
            [
                'expect'         => [
                    SolutionSubType::Strength->value,
                    SolutionSubType::CardioExercise->value,
                    SolutionSubType::Crossfit->value
                ],
                'expect_count'   => 3,
                'tags'           => [
                    TagType::StrongWill->value,
                    TagType::EnoughTime->value
                ],
                'solution_types' => [SolutionType::Fitness->value]
            ],
        ];

        foreach ($expectedData as $data) {
            $this->data = new ConsultingData(
                solutionTypes: $data['solution_types'], tags: $data['tags']
            );

            $this->resultDatas = [];
            $this->initSolutionType();
            $this->searchSolutionSubTypes();
            $results = $this->getMatchingSolution();

//            print_r(array_column($results->toArray(), 'count'));

            $this->assertEquals(
                $data['expect'],
                array_column($results->toArray(), 'solution_type')
            );
            $this->assertEquals($data['expect_count'], count($results));
        }
    }

    /**
     * @test
     * @testdox 정렬 함수 원하는 값으로 리턴되는지 확인
     * @return void
     */
    public function testGetMatchingSolutionCase2(): void
    {
        $this->resultDatas = [
            new ConsultingResultData(SolutionSubType::Crossfit->value, 4),
            new ConsultingResultData(SolutionSubType::LCHF->value, 6),
            new ConsultingResultData(
                SolutionSubType::IntermittentFasting->value, 2
            ),
        ];

        $result = $this->getMatchingSolution();

        $this->assertEquals(
            [
                SolutionSubType::LCHF->value,
                SolutionSubType::Crossfit->value,
                SolutionSubType::IntermittentFasting->value
            ],

            array_column($result->toArray(), 'solution_type')
        );
    }

    /**
     * 선호 타입에 맞는 솔루션 검색
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function searchSolutionSubTypes(): void
    {
        foreach ($this->data->solutionTypes as $solutionType) {
            $this->resultDatas = array_merge(
                $this->factory($solutionType)
                    ->searchSolution($this->data->tags),
                $this->resultDatas
            );
        }
    }

    /**
     * 최종적으로 추천드릴 솔루션 데이터 획득
     * @return \Illuminate\Support\Collection
     */
    private function getMatchingSolution(): Collection
    {
        //내림차순 정렬 태그수가 높은 순으로 전달
        usort(
            $this->resultDatas,
            function (ConsultingResultData $a, ConsultingResultData $b) {
                return $b->count <=> $a->count;
            }
        );


        return collect($this->resultDatas);
    }

    /**
     * @test
     * @testdox 잘못된 솔루션 타입으로 넘긴 경우라면 default 로 FitnessCoach 클래스 획득
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testFactoryWithWrongSolutionType(): void
    {
        $factory = $this->factory(uniqid());

        $this->assertEquals(app()->make(FitnessCoach::class), $factory);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @testdox 다이어트 클래스로 넘어오는지 확인
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testFactoryWithDietExpert(): void
    {
        $factory = $this->factory(SolutionType::Diet->value);

        $this->assertEquals(app()->make(DietExpert::class), $factory);
        $this->assertTrue(true);
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
     * @testdox array_search test code
     * @return void
     */
    public function testArraySearch(): void
    {
        $tags = SolutionSubType::getTags(SolutionSubType::Strength->value);

        $tagIndex = array_search(TagType::StrongWill->value, $tags);

        $this->assertTrue(is_numeric($tagIndex) && $tagIndex === 0);


        $tagIndex = array_search(TagType::EnoughTime->value, $tags);

        $this->assertTrue(is_numeric($tagIndex) && $tagIndex === 1);

        $tagIndex = array_search(TagType::EnoughMoney->value, $tags);

        $this->assertFalse(is_numeric($tagIndex));
    }
}

