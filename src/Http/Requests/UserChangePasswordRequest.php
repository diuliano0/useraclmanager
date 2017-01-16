<?php

namespace BetaGT\UserAclManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserChangePasswordRequest extends FormRequest
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
     * @var array
     */
    public function attributes(){
        return [
            'old_password' => 'Senha Atual',
            'new_password' => 'Nova Senha',
            'new_password_confirmation' => 'Confirmar Nova Senha'
            ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|alphaNum|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ];
    }
}
