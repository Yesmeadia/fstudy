<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FeasibilityApplicationController;
use App\Http\Controllers\FeasibilityChecklistController;
use App\Http\Controllers\FeasibilityWizardController;
use App\Http\Controllers\FieldVisitController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecycleBinController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\FeasibilityApplication;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Legal Pages
Route::view('/privacy-policy', 'legal.privacy')->name('privacy.policy');
Route::view('/terms-and-conditions', 'legal.terms')->name('terms.conditions');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/two-factor', [ProfileController::class, 'toggleTwoFactor'])
        ->name('profile.two-factor')
        ->middleware('throttle:two-factor');
    Route::post('/profile/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])
        ->name('profile.two-factor.confirm')
        ->middleware('throttle:two-factor');
    Route::post('/profile/two-factor/recovery-codes', [ProfileController::class, 'regenerateRecoveryCodes'])
        ->name('profile.two-factor.recovery-codes')
        ->middleware('throttle:two-factor');
    Route::get('/profile/data-export', [ProfileController::class, 'exportData'])
        ->name('profile.data.export')
        ->middleware('throttle:5,1');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Schools & Geolocation
    Route::resource('schools', SchoolController::class)->middleware('throttle:schools');
    Route::post('/geocode/reverse', [GeocodingController::class, 'reverseGeocode'])
        ->name('geocode.reverse')
        ->middleware('throttle:geocoding');
    Route::post('/geocode/search', [GeocodingController::class, 'forwardGeocode'])
        ->name('geocode.search')
        ->middleware('throttle:geocoding');

    // Feasibility Applications
    Route::resource('applications', FeasibilityApplicationController::class)->middleware('throttle:applications');
    Route::post('/applications/{application}/assign', [FeasibilityApplicationController::class, 'assign'])
        ->name('applications.assign')
        ->middleware('throttle:applications');
    Route::post('/applications/{application}/status', [FeasibilityApplicationController::class, 'updateStatus'])
        ->name('applications.status')
        ->middleware('throttle:applications');
    Route::post('/applications/{application}/archive', [FeasibilityApplicationController::class, 'archive'])
        ->name('applications.archive')
        ->middleware('throttle:applications');
    Route::post('/applications/{application}/unarchive', [FeasibilityApplicationController::class, 'unarchive'])
        ->name('applications.unarchive')
        ->middleware('throttle:applications');

    // Running School Takeover Checklist Data Collection (YES India Foundation Format)
    Route::get('/applications/{application}/checklist', [FeasibilityChecklistController::class, 'edit'])->name('applications.checklist.edit');
    Route::post('/applications/{application}/checklist', [FeasibilityChecklistController::class, 'update'])
        ->name('applications.checklist.update')
        ->middleware('throttle:applications');
    Route::post('/applications/{application}/checklist/evidence-photo', [FeasibilityChecklistController::class, 'uploadEvidencePhoto'])
        ->name('applications.checklist.upload_photo')
        ->middleware('throttle:applications');
    Route::post('/applications/{application}/checklist/submit', [FeasibilityChecklistController::class, 'submit'])
        ->name('applications.checklist.submit')
        ->middleware('throttle:applications');

    // Multi-Step Application Wizard (Redirects / bridges to checklist)
    Route::get('/applications/{application}/wizard/{step?}', function (FeasibilityApplication $application, $step = 1) {
        return redirect()->route('applications.checklist.edit', ['application' => $application, 'section' => $step]);
    })->name('applications.wizard.step');
    Route::post('/applications/{application}/wizard/{step}', [FeasibilityChecklistController::class, 'update'])
        ->name('applications.wizard.save')
        ->middleware('throttle:applications');
    Route::delete('/applications/{application}/wizard-delete-nearby/{school}', [FeasibilityWizardController::class, 'deleteNearbySchool'])->name('applications.wizard.delete_nearby');
    Route::delete('/applications/{application}/wizard-delete-capex/{expense}', [FeasibilityWizardController::class, 'deleteCapex'])->name('applications.wizard.delete_capex');
    Route::delete('/applications/{application}/wizard-delete-opex/{expense}', [FeasibilityWizardController::class, 'deleteOpex'])->name('applications.wizard.delete_opex');
    Route::delete('/applications/{application}/wizard-delete-revenue/{revenue}', [FeasibilityWizardController::class, 'deleteRevenue'])->name('applications.wizard.delete_revenue');
    Route::post('/applications/{application}/submit', [FeasibilityChecklistController::class, 'submit'])
        ->name('applications.wizard.submit')
        ->middleware('throttle:applications');

    // Documents
    Route::post('/applications/{application}/documents', [DocumentController::class, 'upload'])
        ->name('documents.upload')
        ->middleware('throttle:uploads');
    Route::post('/documents/{document}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download')
        ->middleware('throttle:downloads');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Field Verification
    Route::post('/applications/{application}/field-visit', [FieldVisitController::class, 'store'])->name('applications.field_visit');

    // Evaluation & Scoring
    Route::post('/applications/{application}/evaluation-scores', [EvaluationController::class, 'storeScores'])->name('applications.evaluation_scores');

    // Risk Assessment
    Route::post('/applications/{application}/risks', [RiskController::class, 'store'])->name('applications.risks.store');
    Route::delete('/risks/{risk}', [RiskController::class, 'destroy'])->name('risks.destroy');

    // Reviews
    Route::post('/applications/{application}/reviews', [ReviewController::class, 'store'])->name('applications.reviews.store');

    // Management Approval
    Route::post('/applications/{application}/approval-decision', [ApprovalController::class, 'decide'])->name('applications.approval_decision');

    // Reports & Export (Protected by throttle:reports to prevent DoS)
    Route::get('/applications/{application}/report/pdf', [ReportController::class, 'downloadPdf'])
        ->name('reports.application.pdf')
        ->middleware('throttle:reports');
    Route::get('/applications/{application}/report/view', [ReportController::class, 'viewPdf'])
        ->name('reports.application.view')
        ->middleware('throttle:reports');
    Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])
        ->name('reports.export.csv')
        ->middleware('throttle:reports');

    // Settings & Audit Logs (Admin / Management)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/purge-expired', [SettingController::class, 'purgeExpiredData'])
        ->name('settings.purge')
        ->middleware('throttle:5,1');
    Route::post('/settings/record-incident', [SettingController::class, 'recordIncident'])
        ->name('settings.incident')
        ->middleware('throttle:5,1');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');

    // Recycle Bin (Super Admin only)
    Route::get('/recycle-bin', [RecycleBinController::class, 'index'])->name('recycle_bin.index');
    Route::post('/recycle-bin/applications/{id}/restore', [RecycleBinController::class, 'restoreApplication'])->name('recycle_bin.restore_application');
    Route::post('/recycle-bin/applications/restore-all', [RecycleBinController::class, 'restoreAllApplications'])->name('recycle_bin.restore_all_applications');
    Route::post('/recycle-bin/schools/{id}/restore', [RecycleBinController::class, 'restoreSchool'])->name('recycle_bin.restore_school');

    // User Management (Super Admin only)
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
