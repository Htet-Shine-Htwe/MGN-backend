<?php

use Illuminate\Support\Facades\Storage;

if(!function_exists('tryCatch')){

    function tryCatch($callback, $message = null,$withException=false){
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

            if($withException)
            {
                $message .= " " . $e->getMessage();
            }

            return response()->json(['message' => $message],$status);
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


if(!function_exists("enumValue")){
    function enumValue($enum){
        return $enum->value;
    }
}
