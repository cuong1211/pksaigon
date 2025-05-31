<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20|regex:/^[0-9]{10,11}$/',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'source' => 'nullable|in:website,phone,walk-in'
        ];

        // Nếu là update, có thể cho phép ngày hẹn trong quá khứ
        if ($this->getMethod() === 'PUT') {
            $rules['appointment_date'] = 'required|date';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'patient_name.required' => 'Tên bệnh nhân là bắt buộc.',
            'patient_name.max' => 'Tên bệnh nhân không được vượt quá 255 ký tự.',
            'patient_phone.required' => 'Số điện thoại là bắt buộc.',
            'patient_phone.regex' => 'Số điện thoại không đúng định dạng (10-11 số).',
            'service_id.exists' => 'Dịch vụ được chọn không tồn tại.',
            'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
            'appointment_date.after' => 'Ngày hẹn phải sau ngày hiện tại.',
            'appointment_time.required' => 'Giờ hẹn là bắt buộc.',
            'symptoms.max' => 'Triệu chứng không được vượt quá 1000 ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'source.in' => 'Nguồn đặt lịch không hợp lệ.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        if (!$this->has('status')) {
            $this->merge(['status' => 'pending']);
        }

        if (!$this->has('source')) {
            $this->merge(['source' => 'phone']); // Backend thường là phone hoặc walk-in
        }

        // Format appointment_time if needed
        if ($this->has('appointment_time')) {
            $time = $this->appointment_time;
            // Ensure time format is HH:MM
            if (strlen($time) === 5 && strpos($time, ':') === 2) {
                // Already in correct format
            } else {
                // Try to format if needed
                $this->merge(['appointment_time' => $time]);
            }
        }
    }

    /**
     * Get formatted data for processing
     */
    public function getProcessedData(): array
    {
        $data = $this->validated();
        
        // Combine date and time for full datetime if needed
        $data['full_datetime'] = $data['appointment_date'] . ' ' . $data['appointment_time'];
        
        return $data;
    }
}