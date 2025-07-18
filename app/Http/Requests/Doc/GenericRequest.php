<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;

class GenericRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->header('token');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "wid" => "required|numeric",
            'page' => 'numeric|min:1|not_in:0',
            'limit' => 'numeric|between:20,500',
        ];
    }

    public function queryParameters()
    {
        return [
            'wid' => [
                'description' => 'The website ID is required to filter the data.',
                'example' => '90644632',
            ],
            'page' => [
                'description' => 'The page is optional, with a default value of 1 and a minimum requirement of 1.',
                'example' => '1',
            ],
            'limit' => [
                'description' => 'The limit is optional, with a default value of 20 and a minimum requirement of 20 and maximum requirement of 500.',
                'example' => '20',
            ],
        ];
    }
}
