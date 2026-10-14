<?php

namespace App\Http\Controllers;

use App\Enums\DocumentStatus;
use App\Http\Requests\UploadDocumentRequest;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\FeasibilityApplication;
use App\Services\Feasibility\DocumentVerificationService;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentVerificationService $documentService,
    ) {}

    public function upload(UploadDocumentRequest $request, FeasibilityApplication $application)
    {
        $this->authorizeDocumentAccess($application, true);

        $requirement = DocumentRequirement::findOrFail($request->requirement_id);
        $file = $request->file('document_file');

        $document = $this->documentService->storeDocument(
            $application,
            $requirement,
            $file,
            $request->user()
        );

        return back()->with('success', "{$requirement->title} uploaded successfully.");
    }

    public function verify(Request $request, Document $document)
    {
        $user = $request->user();
        if (! $user->isReviewer() && ! $user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'verification_status' => ['required', 'in:verified,rejected,under_verification'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $status = DocumentStatus::from($request->verification_status);

        $this->documentService->verifyDocument(
            $document,
            $status,
            $request->remarks,
            $user
        );

        return back()->with('success', "Document status updated to {$status->label()}.");
    }

    public function download(Request $request, Document $document)
    {
        $application = $document->application;
        $this->authorizeDocumentAccess($application, false);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk(DocumentVerificationService::DISK);

        if (! $disk->exists($document->storage_path)) {
            abort(404, 'The requested document file could not be found on storage.');
        }

        AuditLog::log('document_downloaded', $document, null, ['filename' => $document->original_filename]);

        return $disk->download(
            $document->storage_path,
            $document->original_filename
        );
    }

    public function destroy(Document $document)
    {
        $application = $document->application;
        $this->authorizeDocumentAccess($application, true);

        if (! $application->canBeEdited()) {
            return back()->with('error', 'Cannot delete documents from an application under review.');
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk(DocumentVerificationService::DISK);
        if ($disk->exists($document->storage_path)) {
            $disk->delete($document->storage_path);
        }

        AuditLog::log('document_deleted', $document, ['filename' => $document->original_filename], null);
        $document->delete();

        return back()->with('success', 'Document removed successfully.');
    }

    protected function authorizeDocumentAccess(FeasibilityApplication $application, bool $modify = false): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(401);
        }

        if ($user->isSuperAdmin()) {
            return;
        }

        if ($user->isInspector()) {
            if ($application->assigned_to !== $user->id &&
                $application->applicant_user_id !== $user->id &&
                ! $user->hasPermissionTo('documents.download')) {
                abort(403, 'Unauthorized access to application documents.');
            }

            return;
        }

        if ($application->applicant_user_id !== $user->id) {
            abort(403, 'Unauthorized access to application documents.');
        }
    }
}
