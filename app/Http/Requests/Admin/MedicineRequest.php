<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:supplement,medicine,other',
            'description' => 'nullable|string',
            'import_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
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
            'name.required' => 'Tên thuốc là bắt buộc.',
            'name.max' => 'Tên thuốc không được vượt quá 255 ký tự.',
            'type.required' => 'Loại thuốc là bắt buộc.',
            'type.in' => 'Loại thuốc không hợp lệ.',
            'import_price.required' => 'Giá nhập là bắt buộc.',
            'import_price.numeric' => 'Giá nhập phải là số.',
            'import_price.min' => 'Giá nhập không được âm.',
            'sale_price.required' => 'Giá bán là bắt buộc.',
            'sale_price.numeric' => 'Giá bán phải là số.',
            'sale_price.min' => 'Giá bán không được âm.',
            'expiry_date.date' => 'Hạn sử dụng phải là ngày hợp lệ.',
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

        // Parse prices properly
        if ($this->has('import_price') && $this->import_price !== null) {
            $price = str_replace([',', ' '], '', $this->import_price);
            $price = (float) $price;
            $this->merge(['import_price' => $price]);
        }

        if ($this->has('sale_price') && $this->sale_price !== null) {
            $price = str_replace([',', ' '], '', $this->sale_price);
            $price = (float) $price;
            $this->merge(['sale_price' => $price]);
        }

        // Handle expiry_date properly
        if ($this->has('expiry_date') && $this->expiry_date) {
            $date = $this->expiry_date;
            if (strpos($date, '/') !== false) {
                $date = date('Y-m-d', strtotime($date));
            }
            $this->merge(['expiry_date' => $date]);
        }
    }
}
