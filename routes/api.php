<?php

use App\Http\Controllers\Api\V1\BoardController;
use App\Http\Controllers\Api\V1\IssueAttachmentController;
use App\Http\Controllers\Api\V1\IssueCommentController;
use App\Http\Controllers\Api\V1\IssueController;
use App\Http\Controllers\Api\V1\LabelController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\SprintController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\TenantMemberController;
use App\Http\Controllers\Api\V1\WebhookController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TenantInvitationController;
use App\Http\Controllers\Api\V1\VerifyEmailController;
use App\Models\TenantInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth related endpoints
Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware('throttle:5,1')
        ->name('verification.verify');

    Route::post('email/resend', [VerifyEmailController::class, 'resend'])->middleware('throttle:5,1')
        ->name('verification.resend');

    Route::get('/tenants/invitations/accept/{invitation}/{token}', [TenantInvitationController::class, 'accept'])
        ->middleware('throttle:5,1')->name('tenant-invitations.accept');

    Route::post('register-with-invitation', [TenantInvitationController::class, 'registerWithInvitation']);

    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {

    // Projects
    Route::apiResource('projects', ProjectController::class);

    // Boards
    Route::apiResource('boards', BoardController::class);

    // Sprints
    Route::apiResource('sprints', SprintController::class);

    // Issues
    Route::apiResource('issues', IssueController::class);

    // Labels
    Route::apiResource('labels', LabelController::class);

    // Comments
    Route::apiResource('issues.comments', IssueCommentController::class);

    // Attachments
    Route::apiResource('issues.attachments', IssueAttachmentController::class);

    // Webhooks
    Route::apiResource('webhooks', WebhookController::class);

    // Tenant Membership
    Route::post('/tenants/switch', [TenantController::class, 'switchTenant']);
    Route::apiResource('tenants.members', TenantMemberController::class);
    Route::post('/tenants/invite', [TenantInvitationController::class, 'send'])->middleware(['throttle:5,1', 'EnsureTenantContext']);
    Route::get('/tenants/pending-invitations', [TenantInvitationController::class, 'pendingInvitations'])
        ->middleware('EnsureTenantContext')
        ->name('tenant-pending-invitations');

    Route::delete('/tenants/delete-invitation/{invitation}', [TenantInvitationController::class, 'revoke'])
        ->middleware('EnsureTenantContext')
        ->name('tenant-invitations.revoke');

    Route::apiResource('tenants', TenantController::class);

});


