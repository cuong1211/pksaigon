<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
        $patientId = $this->route('patient');
        
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:patients,phone,' . $patientId,
            'address' => 'nullable|string|max:500',
            'citizen_id' => 'nullable|string|max:20|unique:patients,citizen_id,' . $patientId,
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'allergies' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ tên là bắt buộc.',
            'full_name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'citizen_id.unique' => 'Số căn cước công dân đã tồn tại.',
            'citizen_id.max' => 'Số căn cước công dân không được vượt quá 20 ký tự.',
            'date_of_birth.date' => 'Ngày sinh phải là ngày hợp lệ.',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'emergency_contact.max' => 'Tên người liên hệ khẩn cấp không được vượt quá 255 ký tự.',
            'emergency_phone.max' => 'SĐT khẩn cấp không được vượt quá 20 ký tự.',
            'allergies.max' => 'Thông tin dị ứng không được vượt quá 1000 ký tự.',
            'medical_history.max' => 'Tiền sử bệnh không được vượt quá 2000 ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.'
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
                'is_active' => true
            ]);
        }

        // Clean phone number
        if ($this->has('phone')) {
            $phone = preg_replace('/[^0-9]/', '', $this->phone);
            $this->merge(['phone' => $phone]);
        }

        // Clean emergency phone
        if ($this->has('emergency_phone')) {
            $emergencyPhone = preg_replace('/[^0-9]/', '', $this->emergency_phone);
            $this->merge(['emergency_phone' => $emergencyPhone]);
        }
    }
}