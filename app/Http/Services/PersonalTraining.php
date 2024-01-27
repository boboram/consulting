<?php

namespace App\Http\Services;

use App\Http\Data\Consulting\ConsultingData;
use App\Http\Data\Consulting\ConsultingResultData;
use App\Http\Enums\SolutionType;
use Illuminate\Support\Collection;

/**
 * 전문가 클래스, 클라이언트로부터 전달 받은 정보를 통해 적절한 전문가에게 솔루션을 문의하는 역할
 * Class PersonalTraining
 * @package App\Http\Services
 */
class PersonalTraining
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
     * @param \App\Http\Data\Consulting\ConsultingData $data
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function consulting(ConsultingData $data): Collection
    {
        $this->data = $data;

        //솔루션 타입 검사 후 설정
        $this->initSolutionType();

        //선호 타입에 맞는 솔루션 검색
        $this->searchSolutionSubTypes();

        //최종적으로 추천드릴 솔루션 데이터 획득
        return $this->getMatchingSolution();
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
}
