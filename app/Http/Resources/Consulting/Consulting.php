<?php

namespace App\Http\Resources\Consulting;

use Illuminate\Http\Resources\Json\JsonResource;

class Consulting extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'solution_type' => $this->solutionType
        ];
    }
}
