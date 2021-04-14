<?php

namespace Internexus\Watcher\Collectors;

use Internexus\Watcher\Support\Arrayable;

class Host extends Arrayable
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->hostname = gethostname();
        $this->ip = gethostbyname(gethostname());
    }

    /**
     * Sample host memory usage (%).
     *
     * @return false|float|int
     */
    public function getHostMemoryUsage()
    {
        try {
            $free = shell_exec('free');

            if($free === null) {
                return 0;
            }

            $free_arr = explode("\n", (string)trim($free));
            $mem = explode(" ", $free_arr[1]);
            $mem = array_merge(array_filter($mem));
            // used - buffers - cached
            return round((($mem[2]-$mem[5]-$mem[6]) / $mem[1]) * 100, 2);

        } catch (\Throwable $exception) {
            return 0;
        }
    }

    /**
     * Sample host disk usage (%).
     *
     * @return false|float
     */
    public function getHostDiskUsage()
    {
        try {
            return @is_readable('/')
                ? round(100 - ((disk_free_space('/') / disk_total_space('/')) * 100), 2)
                : 0;
        } catch (\Throwable $exception) {
            return 0;
        }
    }

    /**
     * Sample host cpu usage (%).
     *
     * @return false|float|int
     */
    public function getHostCpuUsage()
    {
        try {
            $load = sys_getloadavg()[0];
            $proc = exec('nproc');
            return is_numeric($load) && is_numeric($proc)
                ? round($load * 100 / $proc, 2)
                : 0;
        } catch (\Throwable $exception) {
            return 0;
        }
    }
}
