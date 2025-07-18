<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Query parameters
 */
class OfferShowRequest extends FormRequest
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
        ];
    }

    public function queryParameters()
    {
        return [
            'wid' => [
                'description' => 'The website ID is required to filter the data based on websites.',
                'example' => '90644632',
            ]
        ];
    }
}
