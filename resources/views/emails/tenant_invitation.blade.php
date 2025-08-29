@component('mail::message')
# Invitation to Join {{ $invitation->tenant->name }}

Hello,

You have been invited to join the tenant "{{ $invitation->tenant->name }}" as a "{{ $invitation->role }}".

@component('mail::button', ['url' => url("/api/tenants/invitations/accept/{$plainToken}")])
Accept Invitation
@endcomponent

Or copy and paste this link into your browser:

{{ route('tenant-invitations.accept', [$invitation->id, $plainToken]) }}

This invitation will expire at {{ $invitation->expires_at->toDayDateTimeString() ?? 'N/A' }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
