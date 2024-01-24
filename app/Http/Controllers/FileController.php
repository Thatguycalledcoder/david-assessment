<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function read_cpu_info() {
        // cat /proc/meminfo Memory info
        $cpustats = shell_exec("cat /proc/stat");

        $file = explode("\n", $cpustats);
        $cores = shell_exec("cat /proc/cpuinfo | grep -E '^processor' | wc -l");

        if ($cores) {
            $file = array_splice($file, 0, intval($cores) + 1);
        }

        return response()->json([
            "success" => true,
            "cpuinfo" => $file,
            "num_cores" => $cores
        ]);
    }

    public function read_mem_info() {
        $output = shell_exec("vmstat -s");
        $meminfo = array_slice(explode("\n", $output),0, 5);

        return response()->json([
            "success" => true,
            "meminfo" => $meminfo,
        ]);
    }
}
