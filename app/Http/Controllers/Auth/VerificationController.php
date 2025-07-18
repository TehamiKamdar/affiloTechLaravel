<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/home';

   

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_1' => 'required|integer',
            'code_2' => 'required|integer',
            'code_3' => 'required|integer',
            'code_4' => 'required|integer',
            'code_5' => 'required|integer',
            'code_6' => 'required|integer',
        ]);

        $validator->after(function ($validator) {
            if ($validator->errors()->hasAny([
                'code_1', 'code_2', 'code_3', 'code_4', 'code_5', 'code_6'
            ])) {
                $validator->errors()->add('form', 'All code fields are required and must be integers.');
            }
        });

        $user = User::where('email', $request->user()->email)
                    ->where('verification_code', $request->code_1 . $request->code_2 . $request->code_3 . $request->code_4 . $request->code_5 . $request->code_6)
                    ->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->status = 'active';
            $user->save();

            return redirect('/get-started')->with('status', 'Your email has been verified.');
        }

        return back()->withErrors([
            'verification_code' => 'Invalid verification code.',
        ]);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return false;
        }

        $user = $request->user();
        $user->generateVerificationCode();
        $user->sendEmailVerificationNotificationCode();

        return true;
    }
    
    public function show(){
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        dd('hello');
        Auth::login(User::find($request->id));

        if (!hash_equals((string) $request->id, (string) $request->user()->getKey())) {
            throw new AuthorizationException();
        }

        if (!hash_equals((string) $request->hash, sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson() ? new JsonResponse([], 204) : redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse([], 204) : redirect($this->redirectPath())->with('verified', true);
    }
}
