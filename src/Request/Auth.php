<?php

declare(strict_types=1);

namespace Lisettes\Auth\Request;

use Hyperf\Validation\Request\FormRequest;

class Auth extends FormRequest
{
    /**
     * 验证场景
     *
     * @var string[]
     */
    protected $scenes = [
        'password-login' => ['account', 'password', 'verify_code'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'account'     => ['required', 'phone-or-email', 'min:5', 'max:50'],
            'password'    => ['required', 'alpha_dash', 'min:6', 'max:30'],
            'verify_code' => ['required', 'size:6'],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'account'     => trans('auth.fields.account'),
            'password'    => trans('auth.fields.password'),
            'verify_code' => trans('auth.fields.verify_code')
        ];
    }
}
