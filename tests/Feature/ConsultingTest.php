<?php

namespace Feature;

use App\Http\Enums\SolutionSubType;
use Tests\TestCase;

/**
 * Class ConsultingTest
 * @package Feature
 */
class ConsultingTest extends TestCase
{

    /**
     * @test
     * @testdox 파라미터 없이 요청 보낸 경우 실패 처리
     */
    public function testGetWithoutParams(): void
    {
        $response = $this->get('/api/v1/how-to-lose-weight', []);

        $response->assertJsonStructure(
            [
                "result_code",
                "message",
                "response" => []
            ],
        );

        $result = $this->getResultAry($response);

        $this->assertEquals(1, $result['result_code']);
        $this->assertEquals('파라미터를 확인해주세요.', $result['message']);
    }

    /**
     * @test
     * @testdox 태그 없이 보낸 경우
     */
    public function testGetWithoutTags(): void
    {
        $response = $this->get(
            '/api/v1/how-to-lose-weight?solution_types[0]=FITNESS',
            []
        );

        $response->assertJsonStructure(
            [
                "result_code",
                "message",
                "response" => []
            ],
        );

        $result = $this->getResultAry($response);

        $this->assertEquals(1, $result['result_code']);
    }

    /**
     * @test
     * @testdox 태그 없이 보낸 경우
     */
    public function testGetWithWrongTags(): void
    {
        $response = $this->get('/api/v1/how-to-lose-weight?tags[0]=wrong', []);

        $response->assertJsonStructure(
            [
                "result_code",
                "message",
                "response" => []
            ],
        );

        $result = $this->getResultAry($response);

        $this->assertEquals(1, $result['result_code']);
    }

    /**
     * @test
     * @testdox success case
     */
    public function testSuccess(): void
    {
        $response = $this->get(
            '/api/v1/how-to-lose-weight?solution_types[0]=DIET&tags[0]=enough_time',
            []
        );

        $response->assertJsonStructure(
            [
                "result_code",
                "message",
                "response" => []
            ],
        );

        $result = $this->getResultAry($response);

        $this->assertEquals(0, $result['result_code']);
        $this->assertEquals(
            SolutionSubType::IntermittentFasting->value,
            $result['response']['list'][0]['solution_type']
        );
    }

    /**
     * @test
     * @testdox success case
     */
    public function testSuccessCase2(): void
    {
        $response = $this->get(
            '/api/v1/how-to-lose-weight?tags[0]=enough_time&tags[1]=strong_will',
            []
        );

        $response->assertJsonStructure(
            [
                "result_code",
                "message",
                "response" => []
            ],
        );

        $result = $this->getResultAry($response);

        $this->assertEquals(0, $result['result_code']);
        $this->assertEquals(
            [SolutionSubType::IntermittentFasting->value, SolutionSubType::Strength->value, SolutionSubType::CardioExercise->value, SolutionSubType::Crossfit->value],
            array_column($result['response']['list'], 'solution_type')
        );
    }
}
