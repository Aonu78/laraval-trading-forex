<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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

        $user = User::find(request()->user);

        return [
            'phone' => 'unique:users,phone,' . $user->id,
            'trade_win_rate' => 'required|integer|between:0,100',
            'trade_profit_percent' => 'required|integer|between:0,100',
            'credit_score' => 'required|integer|min:0|max:100',
            'level' => 'required|string|max:50',
            'password' => 'nullable|min:6',
            'plain_password' => 'nullable|string|max:255'
        ];
    }
}
