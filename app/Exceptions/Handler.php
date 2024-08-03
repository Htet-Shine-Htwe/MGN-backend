<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

            if(env('APP_ENV') === 'production'){
                Log::channel('slack')->error($e->getMessage(),[
                    'file' => $e->getFile(),
                    'Line' => $e->getLine(),
                    'code' => $e->getCode(),
                ]);
            }


        });
    }

    public function render($request,Throwable $e)
    {
        if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
            return response()->json([
                'message' => "{$this->prettyModelNotFound($e)} not found"
            ],Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }

    protected function prettyModelNotFound(ModelNotFoundException $exception): string
    {
        if (! is_null($exception->getModel())) {
            return ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($exception->getModel())));
        }

        return 'resource';
    }

}
