<?php

namespace App\Console\Commands;

use App\Enums\ApplicationStatus;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeExpiredDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compliance:purge-expired {--dry-run : Simulate the purge without deleting records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enforce institutional data retention policies by pruning expired draft applications and archived audit logs.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        // Configurable retention periods
        $draftExpiryDays = (int) Setting::get('data_retention_days_draft', 180);
        $auditLogRetentionDays = (int) Setting::get('data_retention_days_audit_logs', 730);

        $draftThreshold = now()->subDays($draftExpiryDays);
        $auditLogThreshold = now()->subDays($auditLogRetentionDays);

        $this->info('Enforcing Data Retention Policies (Dry Run: '.($dryRun ? 'YES' : 'NO').')');
        $this->info("- Abandoned Draft Expiry: Older than {$draftExpiryDays} days ({$draftThreshold->toDateString()})");
        $this->info("- Audit Log Retention: Older than {$auditLogRetentionDays} days ({$auditLogThreshold->toDateString()})");

        // 1. Find abandoned drafts
        $draftsQuery = FeasibilityApplication::where('status', ApplicationStatus::DRAFT)
            ->where('updated_at', '<', $draftThreshold);
        $draftsCount = $draftsQuery->count();

        // 2. Find aged audit logs
        $logsQuery = AuditLog::where('created_at', '<', $auditLogThreshold);
        $logsCount = $logsQuery->count();

        $this->table(
            ['Data Category', 'Retention Policy', 'Records Identified', 'Action'],
            [
                ['Abandoned Draft Applications', "{$draftExpiryDays} days", $draftsCount, $dryRun ? 'Would Purge' : 'Purged'],
                ['Archived System Audit Logs', "{$auditLogRetentionDays} days", $logsCount, $dryRun ? 'Would Purge' : 'Purged'],
            ]
        );

        if ($dryRun) {
            $this->warn('Dry run completed. No database records were modified.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($draftsQuery, $logsQuery, $draftsCount, $logsCount) {
            if ($draftsCount > 0) {
                // Delete associated records through cascade/model delete
                foreach ($draftsQuery->get() as $app) {
                    $app->delete();
                }
            }

            if ($logsCount > 0) {
                $logsQuery->delete();
            }

            AuditLog::log(
                action: 'compliance_retention_purge',
                model: null,
                newValues: [
                    'purged_draft_applications' => $draftsCount,
                    'purged_audit_logs' => $logsCount,
                    'executed_by' => 'Automated Retention Scheduler',
                ]
            );
        });

        $this->info('Compliance data retention purge completed successfully.');

        return self::SUCCESS;
    }
}
