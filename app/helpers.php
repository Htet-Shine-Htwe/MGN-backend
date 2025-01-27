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
