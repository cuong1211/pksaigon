<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date|after:now'
        ];
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
            'patient_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'service_id.exists' => 'Dịch vụ được chọn không tồn tại.',
            'appointment_date.required' => 'Ngày giờ hẹn là bắt buộc.',
            'appointment_date.date' => 'Ngày giờ hẹn phải là ngày hợp lệ.',
            'appointment_date.after' => 'Ngày giờ hẹn phải sau thời điểm hiện tại.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Format appointment_date if provided
        if ($this->has('appointment_date') && $this->appointment_date) {
            // Combine date and time if they're separate
            $date = $this->appointment_date;
            $time = $this->input('appointment_time', '09:00');

            if ($this->has('appointment_time')) {
                $datetime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
                $this->merge([
                    'appointment_date' => $datetime->format('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
