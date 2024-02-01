<?php

namespace App\Http\Resources\Consulting;

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
