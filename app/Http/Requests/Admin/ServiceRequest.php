<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Đã thay đổi từ false thành true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service'); // Lấy ID từ route parameter

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('services', 'slug')->ignore($serviceId)
            ],
            'description' => 'nullable|string',
            'type' => 'required|in:procedure,laboratory,other',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc.',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã được sử dụng.',
            'slug.regex' => 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang.',
            'type.required' => 'Loại dịch vụ là bắt buộc.',
            'type.in' => 'Loại dịch vụ phải là: Thủ thuật, Xét nghiệm hoặc Khác.',
            'price.required' => 'Giá dịch vụ là bắt buộc.',
            'price.numeric' => 'Giá dịch vụ phải là số.',
            'price.min' => 'Giá dịch vụ phải lớn hơn hoặc bằng 0.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Chuyển đổi is_active từ checkbox
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
        ]);

        // Làm sạch slug nếu có
        if ($this->has('slug') && !empty($this->input('slug'))) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->input('slug'))
            ]);
        }
    }
}
