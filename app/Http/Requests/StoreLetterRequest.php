<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreLetterRequest extends FormRequest
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
            'from' => [Rule::requiredIf($this->type == 'incoming')],
            'to' => [Rule::requiredIf($this->type == 'outgoing')],
            'letter_number' => [Rule::requiredIf($this->type == 'incoming'), Rule::unique('letters')],
            'letter_date' => [Rule::requiredIf($this->type == 'outgoing')],
            'received_date' => [Rule::requiredIf($this->type == 'incoming')],
            'content' => [Rule::requiredIf($this->type == 'outgoing')],
            'regarding' => ['required'],
            'note' => ['nullable'],
            'type' => ['required'],
            'classification_code' => ['required'],
        ];
    }
}
