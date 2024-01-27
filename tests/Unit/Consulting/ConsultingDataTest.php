<?php

namespace Tests\Unit\Consulting;

use App\Http\Data\Consulting\ConsultingData;
use App\Http\Enums\SolutionType;
use App\Http\Enums\TagType;
use Tests\TestCase;

/**
 * Class ConsultingDataTest
 * @package Tests\Unit\Consulting
 */
class ConsultingDataTest extends TestCase
{

    /**
     * @test
     * @testdox 프로퍼티 값 없이 데이터 생성하기, 빈 프로퍼티로 들어가도 오류 나지 않도록
     * @return void
     */
    public function testCreateConsultingDataWithoutProperty(): void
    {
        $data = new ConsultingData();

        $this->assertTrue(true);
    }

    /**
     * @test
     * @testdox 태그 값만 넘긴 경우라도 잘 생성되는지 확인
     * @return void
     */
    public function testCreateConsultingDataWithProperty(): void
    {
        $data = new ConsultingData(tags: [TagType::StrongWill->value]);

        $this->assertEquals(TagType::StrongWill->value, $data->tags[0]);

        $this->assertTrue(true);
    }

    /**
     * @test
     * @testdox 모든 값 넘긴 경우 테스트 코드
     * @return void
     */
    public function testCreateConsultingDataWithAllProperty(): void
    {
        $data = new ConsultingData(
            solutionTypes: [SolutionType::Fitness->value],
            tags: [TagType::StrongWill->value]
        );

        $this->assertEquals(
            SolutionType::Fitness->value,
            $data->solutionTypes[0]
        );
        $this->assertEquals(TagType::StrongWill->value, $data->tags[0]);

        $this->assertTrue(true);
    }
}

