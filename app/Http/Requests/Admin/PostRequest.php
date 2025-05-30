<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
        $postId = $this->route('post'); // Lấy ID từ route parameter

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('posts', 'slug')->ignore($postId)
            ],
            'content' => 'required|string',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài viết là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã được sử dụng.',
            'slug.regex' => 'Slug chỉ được chứa chữ cái thường, số và dấu gạch ngang.',
            'content.required' => 'Nội dung bài viết là bắt buộc.',
            'featured_image.image' => 'File phải là hình ảnh.',
            'featured_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'featured_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Chuyển đổi từ checkbox
        $this->merge([
            'status' => $this->has('status') ? true : false,
            'is_featured' => $this->has('is_featured') ? true : false,
        ]);

        // Làm sạch slug nếu có
        if ($this->has('slug') && !empty($this->input('slug'))) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->input('slug'))
            ]);
        }
    }
}