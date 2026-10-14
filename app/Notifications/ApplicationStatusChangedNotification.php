<?php

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\FeasibilityApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public FeasibilityApplication $application,
        public ApplicationStatus $previousStatus,
        public ApplicationStatus $newStatus,
        public ?string $remarks = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = config('app.name', 'YES INDIA FOUNDATION');
        $appNo = $this->application->application_number;
        $statusLabel = $this->newStatus->label();

        return (new MailMessage)
            ->subject("[Status Update] {$appNo}: {$statusLabel} — {$orgName}")
            ->view('emails.applications.status-changed', [
                'user' => $notifiable,
                'application' => $this->application,
                'previousStatus' => $this->previousStatus,
                'newStatus' => $this->newStatus,
                'remarks' => $this->remarks,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'application_number' => $this->application->application_number,
            'school_name' => $this->application->school->name,
            'previous_status' => $this->previousStatus->value,
            'new_status' => $this->newStatus->value,
            'status_label' => $this->newStatus->label(),
            'remarks' => $this->remarks,
            'message' => "Application {$this->application->application_number} status updated to {$this->newStatus->label()}.",
            'url' => route('applications.show', $this->application),
        ];
    }
}
