<?php

namespace App\Mail;

use App\Models\TenantInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public TenantInvitation $invitation;
    public string $plainToken;

    public function __construct(TenantInvitation $invitation, string $plainToken)
    {
        $this->invitation = $invitation;
        $this->plainToken = $plainToken;
    }

    public function build()
    {
        return $this->subject("You're invited to join {$this->invitation->tenant->name}")
                    ->markdown('emails.tenant_invitation');
    }
}
