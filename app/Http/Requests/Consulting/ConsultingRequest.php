<?php

namespace App\Http\Requests\Consulting;

use App\Http\Enums\SolutionType;
use App\Http\Enums\TagType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Class ConsultingRequest
 * @package App\Http\Requests\Consulting
 */
class ConsultingRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'solution_types'   => ['nullable', 'array'],
            'solution_types.*' => [
                Rule::in(
                    SolutionType::getValues()
                )
            ],
            'tags'             => ['required', 'array', 'min:1'],
            'tags.*'           => [
                Rule::in(
                    TagType::getValues()
                )
            ]
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'result_code'   => 1,
            'message'   => '파라미터를 확인해주세요.',
            'response'      => $validator->errors()
        ]));
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance(): Validator
    {
        $data = $this->all();

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}

