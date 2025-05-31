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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Parse total_amount properly - remove formatting nếu có
        if ($this->has('total_amount') && $this->total_amount !== null) {
            $amount = str_replace([',', ' ', 'VNĐ'], '', $this->total_amount);
            $amount = (float) $amount;
            $this->merge(['total_amount' => $amount]);
        }

        // Parse quantity properly
        if ($this->has('quantity') && $this->quantity !== null) {
            $quantity = (int) $this->quantity;
            $this->merge(['quantity' => $quantity]);
        }

        // Handle import_date properly
        if ($this->has('import_date') && $this->import_date) {
            $date = $this->import_date;
            if (strpos($date, '/') !== false) {
                // Convert dd/mm/yyyy to yyyy-mm-dd
                $dateParts = explode('/', $date);
                if (count($dateParts) === 3) {
                    $date = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
                }
            }
            $this->merge(['import_date' => $date]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate that medicine exists and is active
            if ($this->medicine_id) {
                $medicine = \App\Models\Medicine::find($this->medicine_id);
                if (!$medicine || !$medicine->is_active) {
                    $validator->errors()->add('medicine_id', 'Thuốc được chọn không khả dụng.');
                }
            }

            // Validate quantity and total_amount relationship
            if ($this->quantity && $this->total_amount && $this->medicine_id) {
                $medicine = \App\Models\Medicine::find($this->medicine_id);
                if ($medicine && $medicine->import_price > 0) {
                    $expectedTotal = $this->quantity * $medicine->import_price;
                    $actualTotal = $this->total_amount;

                    // Allow small difference due to rounding
                    if (abs($expectedTotal - $actualTotal) > 1) {
                        $validator->errors()->add(
                            'total_amount',
                            'Tổng tiền không khớp với công thức: Số lượng × Giá nhập. ' .
                                'Kỳ vọng: ' . number_format($expectedTotal, 0, '.', '.') . ' VNĐ'
                        );
                    }
                }
            }

            // Validate import_date is not in the future
            if ($this->import_date) {
                $importDate = \Carbon\Carbon::parse($this->import_date);
                if ($importDate->isFuture()) {
                    $validator->errors()->add('import_date', 'Ngày nhập không được là ngày trong tương lai.');
                }
            }
        });
    }
}
