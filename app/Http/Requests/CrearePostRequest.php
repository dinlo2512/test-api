<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CrearePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5',
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'trường name phải nhập',
            'title.required' => 'trường title phải nhập',
            'name.min' => 'trường name phải có ít nhất 5 ký tự',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $reponse = new Response([
            'error' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw (new ValidationException($validator, $reponse));
    }
}
