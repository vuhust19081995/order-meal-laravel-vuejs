<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMealOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'meal_category_id' => 'required',
            'restaurant_id' => 'required',
            'number_of_people' => 'required|min:1|max:10',
            'dishes' => 'required|array',
            'dishes.*.id' => 'required|distinct'
        ];
    }
}
