<?php

namespace App\Listeners;

use App\Events\TenantInvitationCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantInvitationMail;

class SendTenantInvitationEmail
{
    /**
     * Handle the event.
     */
    public function handle(TenantInvitationCreated $event): void
    {
        Mail::to($event->invitation->email)
            ->send(new TenantInvitationMail($event->invitation, $event->plainToken));
    }
}
