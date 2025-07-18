<?php
namespace App\Http\Requests\Publisher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "website_name" => "required",
            "website_url" => ["required", "string", "url", "max:191"],
            "website_type" => "required|array|min:1",
            "categories" => "required|array|min:1",
            "monthly_traffic" => "required",
            "monthly_page_views" => "required",
        ];
    }
}
