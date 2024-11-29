<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trademark' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'delivery' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'insurance' => 'nullable|string|max:255',
            'KMs' => 'nullable|string|max:255',
            'deposit' => 'nullable|string|max:255',
            'min_age' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'daily' => 'nullable|numeric',
            'weekly' => 'nullable|numeric',
            'monthly' => 'nullable|numeric',
            'daily_with_driver' => 'nullable|numeric',
            'weekly_with_driver' => 'nullable|numeric',
            'monthly_with_driver' => 'nullable|numeric',
            'gear' => 'nullable|string|max:255',
            'engine' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'luggage' => 'nullable|integer',
            'sensors' => 'nullable|boolean',
            'bluetooth' => 'nullable|boolean',
            'gcc' => 'nullable|boolean',
            'camera' => 'nullable|boolean',
            'lcd' => 'nullable|boolean',
            'safety' => 'nullable|boolean',
            'radio' => 'nullable|boolean',
            'Mb3_CD' => 'nullable|boolean',
            'date_of_manufacture' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],

            'registration_date' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],

        ];
    }
}
