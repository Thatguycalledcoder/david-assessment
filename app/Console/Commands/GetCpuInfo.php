<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetCpuInfo extends Command
{
    protected $signature = 'cpu:info';
    protected $description = 'Read CPU usage from /proc/stat and store in infostats file';

    public function handle()
    {
        $procStatPath = '/proc/stat';
        $infostatsFilePath = storage_path('infostats');

        // Check if /proc/stat exists
        if (file_exists($procStatPath)) {
            // Read CPU usage from /proc/stat
            $cpuInfo = file_get_contents($procStatPath);

            // Store the information in infostats file
            file_put_contents($infostatsFilePath, $cpuInfo);

            $this->info('CPU information saved to infostats file.');
        } else {
            $this->error('/proc/stat does not exist. Ensure that you are running this on a Linux system.');
        }
    }
}
