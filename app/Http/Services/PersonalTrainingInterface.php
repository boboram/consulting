<?php

namespace App\Http\Services;

use App\Http\Data\Consulting\ConsultingResultData;
use App\Http\Enums\SolutionSubType;
use App\Http\Enums\TagType;

/**
 * Class PersonalTrainingInterface
 * @package App\Http\Services
 */
abstract class PersonalTrainingInterface
{
    /**
     * @var array
     */
    protected array $solutionTypes;

    /**
     * @return array
     */
    public abstract function getSolutionSubTypes(): array;

    /**
     * 넘어온 태그값이 하나라도 일치하는 솔루션들 있다면 리턴
     * @param array $tags
     * @return array
     */
    public function searchSolution(array $tags): array
    {
        $results = [];

        foreach ($this->getSolutionSubTypes() as $solutionSubType) {
            $priorityCnt = $this->getPriorityCnt($solutionSubType, $tags);

            if ($priorityCnt > 0) {
                $results[] = new ConsultingResultData(
                    solutionType: $solutionSubType, count: $priorityCnt
                );
            }
        }

        return $results;
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
