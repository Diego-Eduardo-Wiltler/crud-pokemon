<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreinadorTradeFormRequest extends FormRequest
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
            'treinador:id1' => 'required|exists:treinadores,id|different:treinador:id2',
            'treinador:id2' => 'required|exists:treinadores,id'
        ];
    }

    public function messages()
    {
        return [
            'different' => 'Os treinadores devem ser diferentes para a troca.'
        ];
    }
}
