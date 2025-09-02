<?php

namespace App\Providers;

use App\Models\Project;
use App\Policies\ProjectPolicy;

use App\Models\Issue;
use App\Policies\IssuePolicy;

use App\Models\Board;
use App\Policies\BoardPolicy;

use App\Models\Sprint;
use App\Policies\SprintPolicy;

use App\Models\Label;
use App\Policies\LabelPolicy;

use App\Models\IssueComment;
use App\Policies\IssueCommentPolicy;

use App\Models\IssueAttachment;
use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Policies\IssueAttachmentPolicy;

use App\Models\Webhook;
use App\Policies\TenantInvitationPolicy;
use App\Policies\TenantPolicy;
use App\Policies\WebhookPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Issue::class => IssuePolicy::class,
        Board::class => BoardPolicy::class,
        Sprint::class => SprintPolicy::class,
        Label::class => LabelPolicy::class,
        IssueComment::class => IssueCommentPolicy::class,
        IssueAttachment::class => IssueAttachmentPolicy::class,
        Webhook::class => WebhookPolicy::class,
        TenantInvitation::class => TenantInvitationPolicy::class,
        Tenant::class => TenantPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
