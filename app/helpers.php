<?php

use Illuminate\Support\Facades\Storage;

if(!function_exists('tryCatch')){

    function tryCatch($callback, $message = null){
        try{
            return $callback();
        }catch(\Exception $e){

            $status = 500;
            if($e instanceof \Illuminate\Validation\ValidationException){
                $status = 422;
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ],$status);
            }
            if($message){
                return response()->json(['message' => $message],$status);
            }
            return response()->json(['message' => $e->getMessage()],$status);
        }
    }

}

if(!function_exists('appDriver')){

    function appDriver(){
        return Storage::disk(config('control.mongou_storage'));
    }

}

if(!function_exists('generateSubMogouFolder'))
{
    function generateStorageFolder($prefixFolder,$folder) :string
    {
        return "$prefixFolder/$folder";
    }
}
