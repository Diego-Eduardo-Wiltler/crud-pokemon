<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreinadorUpdateFormRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'regiao' => 'required|string|max:255',
            'tipo_favorito' => 'required|string|max:255',
            'idade' => 'required|integer|min:0',
            'pokemon_id' => 'nullable|integer|exists:pokemons,id',
        ];

    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'regiao.required' => 'A região é obrigatória.',
            'tipo_favorito.required' => 'O tipo favorito é obrigatório.',
            'idade.required' => 'A idade é obrigatória.',
            'idade.integer' => 'A idade deve ser um número inteiro.',
            'idade.min' => 'A idade não pode ser negativa.',
            'pokemon_id.integer' => 'O ID do Pokémon deve ser um número inteiro.',
            'pokemon_id.exists' => 'O ID do Pokémon informado não existe.',
        ];
    }

}
