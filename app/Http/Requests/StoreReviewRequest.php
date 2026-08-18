<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
            'book_id' => $this->route('book')->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::unique('reviews', 'user_id')
                    ->where('book_id', $this->book_id),
            ],

            'book_id' => ['required', 'exists:books,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'この書籍にはすでにレビューを投稿しています',
            
            'rating.required' => '評価を選択してください。',
            'rating.between' => '評価は1～5の範囲で選択してください。',

            'comment.required' => 'コメントを入力してください',
        ];
    }
}
