<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {

                    $user = User::where('email', strtolower($value))->first();

                    if ($user && $user->tenants()->where('tenant_id', $this->user()->current_tenant_id)->exists()) {
                        $fail('This user is already a member of the tenant.');
                    }
                },
            ],
            'role' => ['required', 'in:owner,admin,member,viewer'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be one of: owner, admin, member, viewer.',
        ];
    }
}
