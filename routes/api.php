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
use App\Http\Controllers\VerifyEmailController;
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


Route::prefix('v1')->group(function () {

    // Public endpoints
    Route::post('register', [AuthController::class, 'register']); // create new user
    Route::post('login', [AuthController::class, 'login']);       // login and get token
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']); // optional
    Route::post('reset-password', [AuthController::class, 'resetPassword']);   // optional
    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware('throttle:5,1')
        ->name('verification.verify');

    // Resend verification email
    Route::post('email/resend', [VerifyEmailController::class, 'resend'])->middleware('throttle:5,1')
        ->name('verification.resend');

    // Protected endpoints (require token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']); // revoke token
        Route::get('me', [AuthController::class, 'me']);          // current user info
        Route::post('refresh', [AuthController::class, 'refresh']); // optional if you want refresh logic
    });
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
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('tenants.members', TenantMemberController::class);
});
