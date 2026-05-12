<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-jobs')]
#[Description('Mark expired jobs as expired status')]
class CleanupExpiredJobs extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Marking expired jobs...');
        
        // Get jobs with deadlines that have passed
        $expiredJobs = Job::where('deadline', '<', now())
            ->where('status', 'active')
            ->get();
        
        $expiredJobsCount = 0;
        
        foreach ($expiredJobs as $job) {
            // Only mark as expired, do not delete
            $job->update(['status' => 'expired']);
            $expiredJobsCount++;
            
            $this->line("Marked job as expired: {$job->title} (ID: {$job->id})");
        }
        
        $this->info("Job expiry completed!");
        $this->info("Marked {$expiredJobsCount} jobs as expired");
        
        return Command::SUCCESS;
    }
}
