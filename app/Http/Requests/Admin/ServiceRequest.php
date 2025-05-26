<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service');
        
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:consultation,treatment,examination,surgery',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc.',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'type.required' => 'Loại dịch vụ là bắt buộc.',
            'type.in' => 'Loại dịch vụ không hợp lệ.',
            'price.required' => 'Giá dịch vụ là bắt buộc.',
            'price.numeric' => 'Giá dịch vụ phải là số.',
            'price.min' => 'Giá dịch vụ không được âm.',
            'duration.integer' => 'Thời gian phải là số nguyên.',
            'duration.min' => 'Thời gian phải lớn hơn 0.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox value to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active')
            ]);
        } else {
            $this->merge([
                'is_active' => false
            ]);
        }

        // FIX: Parse price properly to handle decimal values
        if ($this->has('price') && $this->price !== null) {
            // Convert string price to float, handling both "599000" and "599000.00" formats
            $price = str_replace([',', ' '], '', $this->price); // Remove commas and spaces
            $price = (float) $price; // Convert to float (599000.00 becomes 599000.0)
            
            $this->merge([
                'price' => $price
            ]);
        }

        // Convert duration to integer
        if ($this->has('duration') && $this->duration) {
            $this->merge([
                'duration' => (int) $this->duration
            ]);
        }
    }
}