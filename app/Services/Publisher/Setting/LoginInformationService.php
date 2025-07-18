<?php
namespace App\Services\Publisher\Setting;

use App\Helper\Vars;
use App\Models\EmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class LoginInformationService
{
    public function init(Request $request)
    {
        $title = "Login Information";
        $user = $request->user();
        seo()->title(default: "{$title} — " . env("APP_NAME"));
        $publisher = $user->publisher;
        $headings = [
            'Profile',
            $title
        ];

        return view("publisher.settings.login-information", compact('title', 'headings', 'publisher'));
    }

    public function changeUpdateEmail(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|confirmed|email',
            ]);

            $user = auth()->user();

            if ($request->email == $user->email || $request->email == $user->new_email) {
                return redirect()->route('publisher.profile.login-information.change-email')->with("error", "Your given email address already in use.");
            } elseif ($request->email != $user->email && $request->email != $user->new_email) {
                $this->sendEmail($request, $user);
                return redirect()->route('publisher.profile.login-information.change-email')->with("success", "Send Verification Email on old email address. After successful verify then email address will be changed.");
            }

        } catch (\Exception $exception) {
            return redirect()->route('publisher.profile.login-information.change-email')->with("error", $exception->getMessage());
        }
    }

    public function sendEmail(Request $request, User $user)
    {
        $user->update([
            'new_email' => $request->email
        ]);

        EmailJob::create([
            'name' => "Send Email Verify Job",
            'path' => "SendEmailVerifyJob",
            'payload' => json_encode($user),
            'date' => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);
    }

    public function verifyEmail($url)
    {
        $user = User::where('new_email', decrypt($url))->first();

        if ($user) {
            $user->newEmail($user->new_email);

            $user->update([
                'new_email' => null
            ]);

            Session::put("success", "Verification email sent to your new email address. Now verify your new email address.");
        } else {
            Session::put("error", "Link expired! Unable to change email address.");
        }

        return redirect(route("publisher.dashboard"));
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = auth()->user();
            $hashedPassword = $user->password;

            if (Hash::check($request->current_password, $hashedPassword) && !Hash::check($request->password, $hashedPassword)) {
               $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            return redirect()->route('publisher.profile.login-information.change-password')->with("success", "Password Successfully Updated.");
        } catch (\Exception $exception) {
            return redirect()->route('publisher.profile.login-information.change-password')->with("error", $exception->getMessage());
        }
    }
}
