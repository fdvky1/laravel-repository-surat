<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLetterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $attachments = $this->file('attachments');
        return [
            'from' => [Rule::requiredIf($this->type == 'incoming')],
            'to' => [Rule::requiredIf($this->type == 'outgoing')],
            'letter_number' => [Rule::requiredIf($this->type == 'incoming')],
            'letter_date' => [Rule::requiredIf($this->type == 'outgoing')],
            'received_date' => [Rule::requiredIf($this->type == 'incoming')],
            'content' => [
                'nullable',
                Rule::requiredIf(function () use ($attachments) {
                    return $this->type == 'outgoing' && empty($attachments);
                }),
            ],
            'regarding' => ['required'],
            'note' => ['nullable'],
            'type' => ['required'],
            'classification_code' => ['required'],
        ];
    }
}
