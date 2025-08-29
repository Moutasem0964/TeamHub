<?php

namespace App\Events;

use App\Models\TenantInvitation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantInvitationCreated
{
    use Dispatchable, SerializesModels;

    public TenantInvitation $invitation;
    public string $plainToken;

    public function __construct(TenantInvitation $invitation, string $plainToken)
    {
        $this->invitation = $invitation;
        $this->plainToken = $plainToken;
    }
}

