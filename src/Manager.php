<?php

namespace Osi\PhalconCron;

use DateTime;
use Osi\Cron\Manager as CronManager;

class Manager extends CronManager
{
    /**
     * For background jobs.
     */
    protected $processes = [];

    public function addCrontab(string $filename)
    {
        $crontab = new CrontabParser($filename);

        $jobs = $crontab->getJobs();

        foreach ($jobs as $job) {
            $this->add($job);
        }
    }

    /**
     * Run all due jobs in the foreground.
     */
    public function runInForeground(DateTime $now = null) : array
    {
        $jobs = $this->getDueJobs($now);

        $outputs = [];

        foreach ($jobs as $job) {
            $outputs[] = $job->runInForeground();
        }

        return $outputs;
    }

    /**
     * Run all due jobs in the background.
     */
    public function runInBackground(DateTime $now = null) : array
    {
        if (empty($now)) {
            $now = new DateTime();
        }

        $jobs = $this->getDueJobs($now);

        foreach ($jobs as $job) {
            $this->processes[] = $job->runInBackground();
        }

        return $this->processes;
    }

    /**
     * Wait for all jobs running in the background to finish.
     */
    public function wait()
    {
        foreach ($this->processes as $process) {
            $process->wait();
        }
    }

    /**
     * Terminate all jobs running in the background.
     */
    public function terminate()
    {
        foreach ($this->processes as $process) {
            $process->terminate();
        }
    }

    /**
     * Kill all jobs running in the background.
     */
    public function kill()
    {
        foreach ($this->processes as $process) {
            $process->kill();
        }
    }
}
