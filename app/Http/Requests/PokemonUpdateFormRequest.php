<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PokemonUpdateFormRequest extends FormRequest
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
            'ataque' => 'required|int|min:5',
            'defesa' => 'required|int|min:5',
            'vida' =>'required|int|min:0',
            'vida_atual' =>'required|int|min:50',
            'tipo' =>'required|string|max:255',
            'peso' =>'required|string|max:255',
            'localizacao' =>'required|string|max:255',
            'shiny' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'ataque.required' => 'O ataque é obrigatório.',
            'ataque.integer' => 'O ataque deve ser um número inteiro.',
            'ataque.min' => 'O ataque não pode ser menor que 5.',
            'defesa.required' => 'A defesa é obrigatória.',
            'defesa.integer' => 'A defesa deve ser um número inteiro.',
            'defesa.min' => 'A defesa não pode ser menor que 5.',
            'vida.required' => 'A vida é obrigatória.',
            'vida.integer' => 'A vida deve ser um número inteiro.',
            'vida.min' => 'A vida não pode ser menor negativa.',
            'vida_atual.required' => 'A vida atual é obrigatória.',
            'vida_atual.integer' => 'A vida_atual deve ser um número inteiro.',
            'vida_atual.min' => 'A vida_atual não pode ser menor que 50.',
            'tipo.required' => 'O tipo é obrigatório.',
            'regiao.required' => 'A região é obrigatória.',
            'peso.required' => 'O peso é obrigatório.',
            'localizacao.required' => 'A localização é obrigatória.',
            'shiny.required' => 'O campo shiny é obrigatório.',
            'shiny.boolean' => 'O campo shiny deve ser verdadeiro ou falso.',
        ];
    }
}
