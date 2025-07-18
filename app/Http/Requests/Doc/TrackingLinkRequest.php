<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;

class TrackingLinkRequest extends FormRequest
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
            "sub_id" => "sometimes|nullable|string|max:40",
        ];
    }

    public function bodyParameters()
    {
        return [
            'wid' => [
                'description' => 'The website ID is required to filter the data based on websites.',
                'example' => '45174194',
            ],
            'sub_id' => [
                'description' => 'The Sub ID is required to filter the data based on Sub ID.',
                'example' => '980b6fb4-5bd8-11ee-8c99-0242ac120002',
            ],
        ];
    }
}
