<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // Check if the email exists in the database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email Doesn\'t exists.',
                'status' => false
            ], 404);
        }

        // $response = $this->broker()->sendResetLink(
        //     $request->only('email')
        // );
        // if ($response === Password::RESET_LINK_SENT) {
            // Generate the token
            $token = $this->broker()->createToken($user);

            // Send the password reset email
            Mail::to($request->email)->send(new ResetPassword($token,$request->email));

            return response()->json([
                'data' => ['token' => $token],
                'message' => 'Password reset link sent.',
                'status' => true
            ], 200);
        // } else {
        //     return response()->json([
        //         'message' => 'Unable to send password reset link.',
        //         'status' => false
        //     ], 422);
        // }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the validation rules for the email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.api.email');
    }


    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response)], 200)
                    : back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }


}
