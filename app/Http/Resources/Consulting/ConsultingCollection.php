<?php

namespace App\Http\Resources\Consulting;

use App\Enums\RatePlans\RatePlanCategoryType;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConsultingCollection extends ResourceCollection
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => parent::toArray($request)
        ];
    }
}
