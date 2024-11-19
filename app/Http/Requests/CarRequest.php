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
            'trademark' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'delivery' => 'required|string|max:255',
            'details' => 'required|string',
            'insurance' => 'required|string|max:255',
            'KMs' => 'required|string|max:255',
            'deposit' => 'required|string|max:255',
            'min_age' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'daily' => 'required|numeric',
            'weekly' => 'required|numeric',
            'monthly' => 'required|numeric',
            'daily_with_driver' => 'required|numeric',
            'weekly_with_driver' => 'required|numeric',
            'monthly_with_driver' => 'required|numeric',
            'gear' => 'required|string|max:255',
            'engine' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'seats' => 'required|integer',
            'doors' => 'required|integer',
            'luggage' => 'required|integer',
            'sensors' => 'required|boolean',
            'bluetooth' => 'required|boolean',
            'gcc' => 'required|boolean',
            'camera' => 'required|boolean',
            'lcd' => 'required|boolean',
            'safety' => 'required|boolean',
            'radio' => 'required|boolean',
            'Mb3_CD' => 'required|boolean',
            'images' => 'required|array',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
