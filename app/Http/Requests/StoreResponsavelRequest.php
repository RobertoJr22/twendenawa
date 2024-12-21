<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResponsavelRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'tipo_usuario_id' => 'required|exists:tipo_usuarios,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'DataNascimento' => 'required|date',
            'BI' => 'required|string|max:14',
            'telefone' => 'required|string|max:15',
            'endereco' => 'required|string|max:255',
            'sexos_id' => 'required|exists:sexos,id',
        ];
    }
}
