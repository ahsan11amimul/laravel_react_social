<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
/**
 * Determine if the user is authorized to make this request.
 */
public function authorize(): bool
{
    return $this->user()->id !== null;
}

protected function prepareForValidation(){
    $this->merge([
        'user_id' => $this->user()->id
    ]);
}
/**
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
public function rules(): array
{
    return [
        'title' => ['required'],
        'content' => ['required'],
        'user_id' => ['required', 'exists:users,id'],
        'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];
}
}
