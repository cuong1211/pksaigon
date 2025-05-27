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
        $medicineId = $this->route('medicine');
        
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:medicines,code,' . $medicineId,
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'manufacturer' => 'nullable|string|max:255',
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
            'code.required' => 'Mã thuốc là bắt buộc.',
            'code.unique' => 'Mã thuốc đã tồn tại.',
            'code.max' => 'Mã thuốc không được vượt quá 50 ký tự.',
            'unit.required' => 'Đơn vị là bắt buộc.',
            'unit.max' => 'Đơn vị không được vượt quá 50 ký tự.',
            'price.required' => 'Giá thuốc là bắt buộc.',
            'price.numeric' => 'Giá thuốc phải là số.',
            'price.min' => 'Giá thuốc không được âm.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng không được âm.',
            'min_quantity.required' => 'Số lượng tối thiểu là bắt buộc.',
            'min_quantity.integer' => 'Số lượng tối thiểu phải là số nguyên.',
            'min_quantity.min' => 'Số lượng tối thiểu không được âm.',
            'manufacturer.max' => 'Nhà sản xuất không được vượt quá 255 ký tự.',
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

        // FIX: Parse price properly to handle decimal values
        if ($this->has('price') && $this->price !== null) {
            // Convert string price to float, handling both "599000" and "599000.00" formats
            $price = str_replace([',', ' '], '', $this->price); // Remove commas and spaces
            $price = (float) $price; // Convert to float (599000.00 becomes 599000.0)
            
            $this->merge([
                'price' => $price
            ]);
        }

        // FIX: Handle expiry_date properly - ensure it's in Y-m-d format
        if ($this->has('expiry_date') && $this->expiry_date) {
            // If date is already in Y-m-d format, keep it. Otherwise try to convert
            $date = $this->expiry_date;
            
            // If date contains '/', convert to Y-m-d format
            if (strpos($date, '/') !== false) {
                $date = date('Y-m-d', strtotime($date));
            }
            
            $this->merge([
                'expiry_date' => $date
            ]);
        }
    }
}