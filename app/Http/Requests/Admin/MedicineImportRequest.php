<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicineImportRequest extends FormRequest
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
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'import_date' => 'required|date',
            'invoice_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'notes' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'medicine_id.required' => 'Vui lòng chọn thuốc.',
            'medicine_id.exists' => 'Thuốc được chọn không tồn tại.',
            'quantity.required' => 'Số lượng nhập là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'total_amount.required' => 'Tổng tiền là bắt buộc.',
            'total_amount.numeric' => 'Tổng tiền phải là số.',
            'total_amount.min' => 'Tổng tiền không được âm.',
            'import_date.required' => 'Ngày nhập là bắt buộc.',
            'import_date.date' => 'Ngày nhập phải là ngày hợp lệ.',
            'invoice_image.image' => 'File phải là hình ảnh.',
            'invoice_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'invoice_image.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
            'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.'
        ];
    }
}