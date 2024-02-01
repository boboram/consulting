<?php

namespace App\Http\Controllers;

use App\Http\Data\Consulting\ConsultingData;
use App\Http\Requests\Consulting\ConsultingRequest;
use App\Http\Resources\Consulting\ConsultingCollection;
use App\Http\Services\PersonalTraining;
use Illuminate\Http\Response;

/**
 * Class ConsultingController
 * @package App\Http\Controllers
 */
class ConsultingController extends Controller
{

    /**
     * @var \App\Http\Services\PersonalTraining
     */
    private PersonalTraining $personalTraining;

    /**
     * ConsultingController constructor.
     * @param \App\Http\Services\PersonalTraining $personalTraining
     */
    public function __construct(PersonalTraining  $personalTraining)
    {
        $this->personalTraining = $personalTraining;
    }

    /**
     * @param \App\Http\Requests\Consulting\ConsultingRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function consulting(ConsultingRequest $request): Response
    {
        $data = ConsultingData::from($request->all());

        $result = $this->personalTraining->consulting($data);

        return response()->success(new ConsultingCollection($result), Response::HTTP_OK);
    }
}
