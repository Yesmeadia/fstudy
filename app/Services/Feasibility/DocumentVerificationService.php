<?php

namespace App\Services\Feasibility;

use App\Enums\DocumentStatus;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\FeasibilityApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentVerificationService
{
    public const DISK = 'local';

    public const DIRECTORY = 'private/documents';

    /**
     * Store an uploaded document securely with a hashed filename.
     */
    public function storeDocument(
        FeasibilityApplication $application,
        DocumentRequirement $requirement,
        UploadedFile $file,
        User $uploader
    ): Document {
        $extension = $file->getClientOriginalExtension();
        $safeFileName = Str::uuid()->toString().'.'.$extension;
        $path = $file->storeAs(self::DIRECTORY.'/'.$application->id, $safeFileName, self::DISK);

        $document = Document::updateOrCreate(
            [
                'application_id' => $application->id,
                'requirement_id' => $requirement->id,
            ],
            [
                'original_filename' => $file->getClientOriginalName(),
                'storage_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'file_size_bytes' => $file->getSize(),
                'uploaded_by' => $uploader->id,
                'verification_status' => DocumentStatus::UPLOADED,
                'remarks' => null,
            ]
        );

        AuditLog::log(
            action: 'document_uploaded',
            model: $document,
            newValues: ['requirement' => $requirement->title, 'filename' => $file->getClientOriginalName()]
        );

        return $document;
    }

    /**
     * Update verification status for a document.
     */
    public function verifyDocument(
        Document $document,
        DocumentStatus $status,
        ?string $remarks,
        User $verifier
    ): Document {
        $oldStatus = $document->verification_status;
        $document->verification_status = $status;
        $document->verified_by = $verifier->id;
        $document->verified_at = now();
        $document->remarks = $remarks;
        $document->save();

        AuditLog::log(
            action: 'document_verified',
            model: $document,
            oldValues: ['status' => $oldStatus->value],
            newValues: ['status' => $status->value, 'remarks' => $remarks]
        );

        return $document;
    }

    /**
     * Check if all mandatory documents have been uploaded for an application.
     */
    public function getMissingMandatoryRequirements(FeasibilityApplication $application): array
    {
        $mandatoryReqs = DocumentRequirement::where('is_mandatory', true)->get();
        $uploadedReqIds = Document::where('application_id', $application->id)
            ->whereIn('verification_status', [DocumentStatus::UPLOADED, DocumentStatus::UNDER_VERIFICATION, DocumentStatus::VERIFIED])
            ->pluck('requirement_id')
            ->toArray();

        $missing = [];
        foreach ($mandatoryReqs as $req) {
            if (! in_array($req->id, $uploadedReqIds)) {
                $missing[] = $req;
            }
        }

        return $missing;
    }
}
