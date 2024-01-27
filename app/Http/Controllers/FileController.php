<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    private function read_cpu_info() {

        if (request()->input("type") == "single")
            $cpustats = shell_exec("head -n 1 /proc/stat");
        else 
            $cpustats = shell_exec("cat /proc/stat");

        $file = explode("\n", $cpustats);
        $cores = shell_exec("cat /proc/cpuinfo | grep -E '^processor' | wc -l");

        if ($cores) {
            $file = array_splice($file, 0, intval($cores) + 1);
        }

        return [$file, $cores];
    }

    private function read_mem_info() {
        $output = shell_exec("vmstat -s");
        $meminfo = array_slice(explode("\n", $output),0, 5);

        return $meminfo;
    }

    public function read_cpu_mem_info() {
        $cpu_info = $this->read_cpu_info();

        return response()->json([
            "success" => true,
            "cpuinfo" => $cpu_info[0],
            "meminfo" => $this->read_mem_info(),
            "cores" => $cpu_info[1],
        ], 200);
    }

    public function read_storage_info() {
        // Indexs for storage: 0 - Size; 1 - Used; 2- Free
        $storagestats = array_slice(explode(" ",shell_exec("df -h | sed -n '3p'")), 1);

        return response()->json([
            "success" => true,
            "storage" => $storagestats
        ], 200);
    }
    
}
