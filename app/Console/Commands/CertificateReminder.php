<?php

namespace App\Console\Commands;

use App\Mail\CertificateExpirationReminder;
use App\Models\Certificate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CertificateReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:certificate-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder Email to Participants, HTD, and PIC for Certificate Expiration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths(2);

        $this->info("Searching for certificates expiring between {$startDate} and {$endDate}...");

        // Add where reminded is null/false to only get certificates that haven't been reminded
        $certificates = Certificate::whereBetween('certificate_expire', [$startDate, $endDate])
            ->where('reminded', false) // or whereNull('reminded') depending on your column type
            ->with(['employee', 'diklat', 'diklat.unit'])
            ->get();

        $this->info("Found {$certificates->count()} certificates that need reminders.");

        $successCount = 0;
        $failCount = 0;

        foreach ($certificates as $certificate) {
            try {
                $recipientEmails = [];

                // 1. Add employee email (certificate owner)
                if ($certificate->employee && $certificate->employee->email) {
                    $recipientEmails[] = $certificate->employee->email;
                }

                // 2. Add HTD of the unit from users table
                if ($certificate->diklat && $certificate->diklat->unit_id) {
                    $unitId = $certificate->diklat->unit_id;
                    $htdUser = User::where('unit_id', $unitId)
                        ->where('role_id', 1)
                        ->first();

                    if ($htdUser && $htdUser->email) {
                        $recipientEmails[] = $htdUser->email;
                    }
                }

                // 3. Add PIC of the area from users table
                if ($certificate->diklat && $certificate->diklat->area_id) {
                    $areaId = $certificate->diklat->area_id;
                    $picUser = User::where('area_id', $areaId)
                        ->where('role_id', 3) // PIC role
                        ->first();

                    if ($picUser && $picUser->email) {
                        $recipientEmails[] = $picUser->email;
                    }
                }

                // Filter out any duplicate emails
                $recipientEmails = array_unique($recipientEmails);

                if (count($recipientEmails) > 0) {
                    Mail::to($recipientEmails)
                        ->send(new CertificateExpirationReminder($certificate));

                    $certificate->update(['reminded' => true]);

                    $this->info("Sent reminder for certificate #{$certificate->certificate_number} to " . implode(", ", $recipientEmails));
                    $successCount++;
                } else {
                    $this->warn("No recipients found for certificate #{$certificate->certificate_number}");
                    $failCount++;
                }
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for certificate #{$certificate->certificate_number}: {$e->getMessage()}");
                $failCount++;
            }
        }

        $this->info("Reminder process completed. Success: {$successCount}, Failed: {$failCount}");

        return Command::SUCCESS;
    }
}
