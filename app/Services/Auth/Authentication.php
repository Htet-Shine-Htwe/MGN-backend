<?php

namespace App\Services\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Authentication
{
    protected string $authType = 'web';

    public function __construct(protected Request $request)
    {
    }

    /**
     * Set the authentication type (web or API).
     */
    public function returnResponse(string $authType = 'web'): self
    {
        $this->authType = $authType;
        return $this;
    }

    /**
     * Handle the sign-in process and generate an appropriate response.
     */
    public function signIn(string $guard = 'web', string $path = '/dashboard'): RedirectResponse|JsonResponse
    {
        try {
            $this->authenticate($guard);
            return $this->signInResponse($path, $guard);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        }
    }

    /**
     * Handle the sign-up process and generate a redirect response.
     */
    public function signUp(Model $model, array $body, string $redirect, string $message = 'Registered Successfully!'): RedirectResponse
    {
        try {
            $model::create($body);
            return $this->signUpSuccessResponse($redirect, $message);
        } catch (\Exception $e) {
            return $this->handleSignUpException($e);
        }
    }

    /**
     * Handle the sign-out process and generate a response based on the authentication type.
     */
    public function signOut(string $path = '/'): RedirectResponse|JsonResponse
    {
        return match ($this->authType) {
            'web' => $this->handleWebSignOut($path),
            'api' => $this->handleApiSignOut(),
        };
    }

    /**
     * Handle the password change process and generate a JSON response.
     */
    public function changePassword(Model $model, FormRequest $request): JsonResponse
    {
        $this->matchPassword($model, $request->old_password);

        $model::find(auth()->id())->update(
            [
            'password' => bcrypt($request->password)
            ]
        );

        return response()->json(
            [
            'message' => 'Password was updated successfully'
            ]
        );
    }

    /**
     * Authenticate the user using the specified guard.
     */
    protected function authenticate(string $guard): void
    {
        $throttle = $this->initializeThrottle();

        if (!$this->attemptLogin($guard)) {
            $throttle->hit();
            throw ValidationException::withMessages(
                [
                'message' => trans('auth.failed'),
                ]
            );
        }

        $throttle->clear();
    }

    /**
     * Generate a response for successful sign-in.
     */
    protected function signInResponse(string $path, string $guard = 'web'): RedirectResponse|JsonResponse
    {
        return $this->fnResponse(
            fn() => $this->regenerateSessionAndRedirect($path),
            $this->generateApiResponseData($guard)
        );
    }

    /**
     * Handle the validation exception and generate a JSON response.
     */
    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        return response()->json(
            [
            'message' => $e->getMessage(),
            ], $e->status ?? 403
        );
    }

    /**
     * Handle the sign-up exception and throw a validation exception.
     */
    protected function handleSignUpException(\Exception $e): RedirectResponse
    {
        throw ValidationException::withMessages(
            [
            'message' => $e->getMessage(),
            ]
        );
    }

    /**
     * Generate a success response for sign-up.
     */
    protected function signUpSuccessResponse(string $redirect, string $message): RedirectResponse
    {
        return redirect($redirect)->with(
            [
            'alert' => [
                'type' => 'success',
                'message' => $message,
            ]
            ]
        );
    }

    /**
     * Check if the old password matches the current user's password.
     */
    protected function matchPassword(Model $model, string $old_password): void
    {
        if (!Hash::check($old_password, $model::find(auth()->id())->password)) {
            throw ValidationException::withMessages(
                [
                'message' => 'Old Password is Incorrect'
                ]
            );
        }
    }

    /**
     * Attempt to log in the user with the provided credentials.
     */
    protected function attemptLogin(string $guard): bool
    {
        return Auth::guard($guard)->attempt(
            $this->request->only('email', 'password'),
            $this->request->boolean('remember')
        );
    }

    /**
     * Regenerate the session and redirect the user to the intended path.
     */
    protected function regenerateSessionAndRedirect(string $path): RedirectResponse
    {
        $this->request->session()->regenerate();
        return redirect()->intended($path);
    }

    /**
     * Generate the API response data.
     */
    protected function generateApiResponseData(string $guard): array
    {
        return [
            'token' => auth()->guard($guard)->user()->createToken($guard)->plainTextToken,
            'user' => auth()->guard($guard)->user()
        ];
    }

    /**
     * Handle the response based on the authentication type (web or API).
     */
    protected function fnResponse(callable $callback, array $data = []): JsonResponse|RedirectResponse
    {
        return match ($this->authType) {
            'web' => $callback(),
            'api' => response()->json($data)
        };
    }

    /**
     * Initialize the throttle object.
     */
    protected function initializeThrottle(): AuthRequestThrottle
    {
        return new AuthRequestThrottle($this->request->input('email'), $this->request->ip());
    }

    /**
     * Invalidate and regenerate the session token.
     */
    protected function invalidateSession(): void
    {
        $this->request->session()->invalidate();
        $this->request->session()->regenerateToken();
    }

    /**
     * Handle web sign-out process.
     */
    protected function handleWebSignOut(string $path): RedirectResponse
    {
        Auth::guard('web')->logout();
        $this->invalidateSession();
        return redirect($path);
    }

    /**
     * Handle API sign-out process.
     */
    protected function handleApiSignOut(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(
            [
            'message' => 'Logged out successfully'
            ]
        );
    }
}
