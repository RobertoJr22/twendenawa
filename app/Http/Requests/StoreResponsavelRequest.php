<?php

namespace App\Http\Requests;


use App\Rules\NBI;
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
            'password' => 'required|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'DataNascimento' => 'required|date|before_or_equal:' . now()->subYears(20)->format('Y-m-d'),
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:15',
            'tipo_usuario_id' => 'required|exists:tipo_usuarios,id',
            'BI' => ['required', New NBI],
        ];
    }
}