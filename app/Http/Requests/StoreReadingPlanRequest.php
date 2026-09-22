<?php

namespace App\Http\Requests;

use App\Enums\ReadingPlanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReadingPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', 'exists:books,id', Rule::unique('reading_plans', 'book_id')->where('user_id', auth()->id()),],
            'target_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => '書籍を選択してください',
            'book_id.exists' => "登録済みの書籍を選択してください",
            'book_id.unique' => "この書籍はすでに読書計画に登録されています",

            'target_date.required' => '読了予定日を入力してください',
            'target_date.after_or_equal' => '読了予定日は本日以降の日付を入力してください',
        ];
    }
}