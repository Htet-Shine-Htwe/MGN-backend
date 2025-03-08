<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

if(!function_exists('tryCatch')) {

    function tryCatch(callable $callback,?string $message = null,bool $withException=false)  : mixed
    {
        try{
            return $callback();
        }catch(\Exception $e){

            $status = 500;
            if($e instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                return response()->json(
                    [
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                    ], $status
                );
            }

            if($withException) {
                $message .= " " . $e->getMessage();
            }

            return response()->json(['message' => $message], $status);
        }
    }

}

if(!function_exists('appDriver')) {

    function appDriver(): \Illuminate\Contracts\Filesystem\Filesystem
{
    $disk = config('control.mongou_storage');
    if (!is_string($disk)) {
        $disk = 'local';
    }
    return Storage::disk($disk);
}

}

if(!function_exists('generateSubMogouFolder')) {
    function generateStorageFolder(string $prefixFolder,string $folder) :string
    {
        return "$prefixFolder/$folder";
    }
}


if (!function_exists("enumValue")) {
    function enumValue(mixed $enum): mixed
    {
        return ($enum instanceof \BackedEnum ? $enum->value : $enum);
    }
}


if(!function_exists("fGetUptime")){
    function fGetUptime( ){
        $uptime = '';

        if(is_readable('/proc/uptime')){
            $str   = @file_get_contents('/proc/uptime');
            $num   = floatval($str);
            $secs  = $num % 60;
            $num   = (int)($num / 60);
            $mins  = $num % 60;
            $num   = (int)($num / 60);
            $hours = $num % 24;
            $num   = (int)($num / 24);
            $days  = $num;

            $uptime = $days.' days, '.$hours.' hours & '.$mins.' minutes';
            } // /is_readable('/proc/uptime')

        return $uptime;
    }
}

if(!function_exists('formatBytes')){
    function formatBytes($bytes)
{
    $units = ['B','KB','MB','GB','TB','PB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2).' '.$units[$i];
}
}
