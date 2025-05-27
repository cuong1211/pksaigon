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
            'services' => 'nullable', // Cho phép cả string JSON và array
            'medicines' => 'nullable', // Cho phép cả string JSON và array
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

        // Process services - handle both array and JSON string
        if ($this->has('services')) {
            $services = $this->input('services');

            if (is_string($services)) {
                // Parse JSON string
                try {
                    $servicesArray = json_decode($services, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($servicesArray)) {
                        $validatedServices = [];
                        foreach ($servicesArray as $service) {
                            if (isset($service['service_id']) && isset($service['quantity']) && isset($service['price'])) {
                                $validatedServices[] = [
                                    'service_id' => (int) $service['service_id'],
                                    'quantity' => (int) $service['quantity'],
                                    'price' => (float) $service['price']
                                ];
                            }
                        }
                        $this->merge(['services' => $validatedServices]);
                    } else {
                        $this->merge(['services' => []]);
                    }
                } catch (\Exception $e) {
                    $this->merge(['services' => []]);
                }
            } elseif (is_array($services)) {
                // Already an array, just validate structure
                $validatedServices = [];
                foreach ($services as $service) {
                    if (isset($service['service_id']) && isset($service['quantity']) && isset($service['price'])) {
                        $validatedServices[] = [
                            'service_id' => (int) $service['service_id'],
                            'quantity' => (int) $service['quantity'],
                            'price' => (float) $service['price']
                        ];
                    }
                }
                $this->merge(['services' => $validatedServices]);
            }
        }

        // Process medicines - handle both array and JSON string
        if ($this->has('medicines')) {
            $medicines = $this->input('medicines');

            if (is_string($medicines)) {
                // Parse JSON string
                try {
                    $medicinesArray = json_decode($medicines, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($medicinesArray)) {
                        $validatedMedicines = [];
                        foreach ($medicinesArray as $medicine) {
                            if (isset($medicine['medicine_id']) && isset($medicine['quantity']) && isset($medicine['price'])) {
                                $validatedMedicines[] = [
                                    'medicine_id' => (int) $medicine['medicine_id'],
                                    'quantity' => (int) $medicine['quantity'],
                                    'dosage' => $medicine['dosage'] ?? '',
                                    'note' => $medicine['note'] ?? '',
                                    'price' => (float) $medicine['price']
                                ];
                            }
                        }
                        $this->merge(['medicines' => $validatedMedicines]);
                    } else {
                        $this->merge(['medicines' => []]);
                    }
                } catch (\Exception $e) {
                    $this->merge(['medicines' => []]);
                }
            } elseif (is_array($medicines)) {
                // Already an array, just validate structure
                $validatedMedicines = [];
                foreach ($medicines as $medicine) {
                    if (isset($medicine['medicine_id']) && isset($medicine['quantity']) && isset($medicine['price'])) {
                        $validatedMedicines[] = [
                            'medicine_id' => (int) $medicine['medicine_id'],
                            'quantity' => (int) $medicine['quantity'],
                            'dosage' => $medicine['dosage'] ?? '',
                            'note' => $medicine['note'] ?? '',
                            'price' => (float) $medicine['price']
                        ];
                    }
                }
                $this->merge(['medicines' => $validatedMedicines]);
            }
        }
    }
}
