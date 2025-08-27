<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegisterRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        // Normalize inputs
        $input = $this->all();

        if (array_key_exists('email', $input)) {
            $input['email'] = is_string($input['email']) ? strtolower(trim($input['email'])) : $input['email'];
        }

        // Accept "create_tenant" coming as "true"/"false" strings from JS
        if (array_key_exists('create_tenant', $input)) {
            $input['create_tenant'] = filter_var($input['create_tenant'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $input['create_tenant'] = false;
        }

        $this->replace($input);
    }

    public function rules()
    {
        // base rules always applied
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'invite_token' => ['nullable', 'string'], // token checked later in controller
            'create_tenant' => ['sometimes', 'boolean'],
        ];

        // conditional rules: when create_tenant is true
        if ($this->boolean('create_tenant')) {
            $rules = array_merge($rules, [
                'tenant_name' => ['required', 'string', 'max:255'],
                'plan' => ['required', Rule::in(['free', 'plus'])]
                // 'tenant_slug' => [
                //     'nullable',
                //     'string',
                //     'max:255',
                //     // slug pattern: letters, numbers, hyphens, underscores
                //     'regex:/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/i',
                //     Rule::unique('tenants', 'slug'),
                // ],
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    public function attributes()
    {
        return [
            'tenant_name' => 'organization name',
            'invite_token' => 'invitation token',
        ];
    }
}
