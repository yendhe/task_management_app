<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|unique:tasks|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:New,Incomplete,Complete',
            'priority' => 'required|in:High,Medium,Low',
            'notes' => 'required|array|min:1',
            'notes.*.subject' => 'required|unique:notes|string|max:255',
            'notes.*.note' => 'nullable|string',
            'notes.*.attachment.*' => 'nullable',
        ];
    }

}
