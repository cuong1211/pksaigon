<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExaminationRequest extends FormRequest
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
        $rules = [
            'examination_date' => 'required|date',
            'services' => 'nullable',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.price' => 'required|integer|min:0',
            'medicines' => 'nullable',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.dosage' => 'nullable|string|max:255',
            'medicines.*.note' => 'nullable|string|max:255',
            'medicines.*.price' => 'required|integer|min:0',
            'diagnosis' => 'nullable|string|max:1000',
            'symptoms' => 'nullable|string|max:1000',
            'treatment_plan' => 'nullable|string|max:2000',
            'next_appointment' => 'nullable|date|after:examination_date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'nullable|in:waiting,examining,completed,cancelled'
        ];

        // Nếu patient_id rỗng thì cần thông tin bệnh nhân mới
        if (empty($this->input('patient_id'))) {
            $rules = array_merge($rules, [
                'patient_name' => 'required|string|max:255',
                'patient_phone' => 'required|string|max:20',
                'patient_address' => 'nullable|string|max:500',
                'patient_dob' => 'nullable|date|before:today',
                'patient_gender' => 'nullable|in:male,female,other',
                'patient_citizen_id' => 'nullable|string|max:20|unique:patients,citizen_id'
            ]);
        } else {
            $rules['patient_id'] = 'required|exists:patients,id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Vui lòng chọn bệnh nhân.',
            'patient_id.exists' => 'Bệnh nhân không tồn tại.',
            'patient_name.required' => 'Họ tên bệnh nhân là bắt buộc.',
            'patient_phone.required' => 'Số điện thoại bệnh nhân là bắt buộc.',
            'patient_phone.unique' => 'Số điện thoại đã tồn tại.',
            'patient_citizen_id.unique' => 'Số căn cước công dân đã tồn tại.',
            'patient_dob.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'patient_gender.in' => 'Giới tính không hợp lệ.',
            'examination_date.required' => 'Ngày khám là bắt buộc.',
            'examination_date.date' => 'Ngày khám phải là ngày hợp lệ.',
            'services.array' => 'Dịch vụ phải là mảng.',
            'services.*.service_id.required' => 'ID dịch vụ là bắt buộc.',
            'services.*.service_id.exists' => 'Dịch vụ không tồn tại.',
            'services.*.quantity.required' => 'Số lượng dịch vụ là bắt buộc.',
            'services.*.quantity.integer' => 'Số lượng dịch vụ phải là số nguyên.',
            'services.*.quantity.min' => 'Số lượng dịch vụ phải lớn hơn 0.',
            'services.*.price.required' => 'Giá dịch vụ là bắt buộc.',
            'services.*.price.integer' => 'Giá dịch vụ phải là số nguyên.',
            'services.*.price.min' => 'Giá dịch vụ không được âm.',
            'medicines.array' => 'Thuốc phải là mảng.',
            'medicines.*.medicine_id.required' => 'ID thuốc là bắt buộc.',
            'medicines.*.medicine_id.exists' => 'Thuốc không tồn tại.',
            'medicines.*.quantity.required' => 'Số lượng thuốc là bắt buộc.',
            'medicines.*.quantity.integer' => 'Số lượng thuốc phải là số nguyên.',
            'medicines.*.quantity.min' => 'Số lượng thuốc phải lớn hơn 0.',
            'medicines.*.dosage.max' => 'Liều dùng không được vượt quá 255 ký tự.',
            'medicines.*.note.max' => 'Ghi chú thuốc không được vượt quá 255 ký tự.',
            'medicines.*.price.required' => 'Giá thuốc là bắt buộc.',
            'medicines.*.price.integer' => 'Giá thuốc phải là số nguyên.',
            'medicines.*.price.min' => 'Giá thuốc không được âm.',
            'diagnosis.max' => 'Chuẩn đoán không được vượt quá 1000 ký tự.',
            'symptoms.max' => 'Triệu chứng không được vượt quá 1000 ký tự.',
            'treatment_plan.max' => 'Kế hoạch điều trị không được vượt quá 2000 ký tự.',
            'next_appointment.date' => 'Lịch tái khám phải là ngày hợp lệ.',
            'next_appointment.after' => 'Lịch tái khám phải sau ngày khám.',
            'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
            'status.in' => 'Trạng thái không hợp lệ.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number for new patient
        if ($this->has('patient_phone')) {
            $phone = preg_replace('/[^0-9]/', '', $this->patient_phone);
            $this->merge(['patient_phone' => $phone]);
        }

        // Process services array
        if ($this->has('services') && is_array($this->services)) {
            $services = array_map(function ($service) {
                return [
                    'service_id' => (int) $service['service_id'],
                    'quantity' => (int) ($service['quantity'] ?? 1),
                    'price' => (int) ($service['price'] ?? 0)
                ];
            }, $this->services);
            $this->merge(['services' => $services]);
        }

        // Process medicines array
        if ($this->has('medicines') && is_array($this->medicines)) {
            $medicines = array_map(function ($medicine) {
                return [
                    'medicine_id' => (int) $medicine['medicine_id'],
                    'quantity' => (int) ($medicine['quantity'] ?? 1),
                    'dosage' => $medicine['dosage'] ?? '',
                    'note' => $medicine['note'] ?? '',
                    'price' => (int) ($medicine['price'] ?? 0)
                ];
            }, $this->medicines);
            $this->merge(['medicines' => $medicines]);
        }
    }
}