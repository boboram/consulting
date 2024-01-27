<?php

namespace Tests\Unit\Consulting;

use App\Http\Enums\SolutionType;
use App\Http\Enums\TagType;
use App\Http\Requests\Consulting\ConsultingRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Class ConsultingRequestTest
 * @package Tests\Unit\Consulting
 */
class ConsultingRequestTest extends TestCase
{

    /**
     * @test
     * @testdox 모든 파라미터 값이 넘어오지 않은 경우 실패 처리된다.
     * @return void
     */
    public function testFailedWithoutParams(): void
    {
        $params = [];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     * @testdox tags 파라미터는 필수 값이기 때문에 보내지 않은 경우 실패처리된다.
     * @return void
     */
    public function testFailedWithoutTag(): void
    {
        $params = ['solution_types' => SolutionType::getValues()];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     * @testdox 잘못된 태그 값으로 보내는 경우 실패 처리 된다.
     * @return void
     */
    public function testFailedWithWrongTag(): void
    {
        $params = [
            'solution_types' => SolutionType::getValues(),
            'tags'           => ['trash value']
        ];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     * @testdox 잘못된 solution type 값으로 보내는 경우 실패 처리 된다.
     * @return void
     */
    public function testFailedWithWrongSolutionType(): void
    {
        $params = [
            'solution_types' => ['trash value'],
            'tags'           => TagType::getValues()
        ];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     * @testdox solution type 값을 보내지 않더라고 tag 값을 보낸다면 성공 처리된다.
     * @return void
     */
    public function testSuccessWithRightTag(): void
    {
        $params = ['tags' => TagType::getValues()];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertFalse($validator->fails());
    }

    /**
     * @test
     * @testdox
     * @return void
     */
    public function testSuccessWithAllData(): void
    {
        $params = [
            'solution_type' => SolutionType::getValues(),
            'tags'          => TagType::getValues()
        ];

        $validator = Validator::make(
            $params,
            $this->getConsultingRequestRule()
        );

        $this->assertFalse($validator->fails());
    }


    /**
     * consulting request class rules 데이터 획득 함수
     * @return array
     */
    private function getConsultingRequestRule(): array
    {
        return (new ConsultingRequest())->rules();
    }
}

