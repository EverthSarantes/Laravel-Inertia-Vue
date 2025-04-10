<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()->with([
                'message' => [
                    'message' => $validator->errors()->first(),
                    'type' => 'danger'
                ],
            ])
        );
    }
}