<?php

namespace App\Services\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Authentication
{
    protected string $type = "web";


    // constructor with request
    public function __construct(protected Request $request)
    {
    }

    public function returnResponse(string $type = "web") : Authentication
    {
        $this->type = $type;
        return $this;
    }

    public function signInResponse(string $path,string $guard ='web') : RedirectResponse | JsonResponse
    {
        if($this->type == "api")
        {
            return response()->json([
                'token' => auth()->guard($guard)->user()->createToken($guard)->plainTextToken,
                'user' => auth()->guard($guard)->user()
            ]);
        }
        else{

            $this->request->session()->regenerate();

            return redirect()->intended($path);
        }
    }


    public function signIn(string $guard ="web",string $path = '/dashboard') :  RedirectResponse| JsonResponse
    {
        try{
            $this->authenticate($guard);

        }
        catch (ValidationException $e){
           return response()->json([
               'message' => $e->getMessage()
           ],$e->status ?? 403);
        }

        return $this->signInResponse($path,$guard);
    }

    public function signUp(Model $model,array $body,string $redirect,$message  = "Registered Successfully!") : RedirectResponse
    {
        try{
            $model::create($body);

            return redirect($redirect)->with([
                'alert' => [
                    'type' => 'success',
                    'message' => $message
                ]
            ]);
        }
        catch (\Exception $e){
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function signOut(string $guard = "web",string $path ="/") : RedirectResponse
    {
        Auth::guard($guard)->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        // session_alert('success','Logout Successfully');

        return redirect($path);
    }

    public function authenticate(string $guard): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::guard($guard)->attempt($this->request->only('email', 'password'), $this->request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // session_alert('error','Login Failed');

            throw ValidationException::withMessages([
                'message' => trans('auth.failed'),
            ]);
        }


        RateLimiter::clear($this->throttleKey());
    }


    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'message' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        $request= $this->request;
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    public function changePassword(Model $model,FormRequest $request) : JsonResponse
    {
        $old_password = $request->old_password;

        $this->matchPassword($model,$old_password);

        $model::find(auth()->id())->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' =>  "Password was updated successfully"
        ]);
    }

    protected function matchPassword(Model $model,string $old_password) : void
    {
        if(!Hash::check($old_password,$model::find(auth()->id())->password))
        {
            throw ValidationException::withMessages([
                'message' => 'Old Password is Incorrect'
            ]);
        }
    }

}
