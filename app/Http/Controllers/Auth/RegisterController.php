<?php
namespace App\Http\Controllers\Auth;

use App\Helper\Methods;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Mail\VerifyEmail;
use Anhskohbo\NoCaptcha\NoCaptcha;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'terms' => ['accepted'],
            'user_name' => [
                'required',
                'string',
                'max:191',
                ]
        ]);
    }

    protected function create(array $data)
    {

 if (strlen($data['g-recaptcha-response']) > 1000) {
            $user = User::create([
            'publisher_id' => Str::uuid()->toString(),
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'type' => User::PUBLISHER,
            'status'=>'pending',
            'api_token' => Str::random(30),
            'user_name' => $data['user_name']
             'recaptcha_response' => $data['g-recaptcha-response']
        ]);
         $user->generateVerificationCode();
        $role = Role::firstOrCreate(['name' => User::PUBLISHER]);
        $user->assignRole(User::PUBLISHER);

        return $user;
 }

        }







    protected function registered(Request $request, $user)
    {

        $verification_code = rand(100000, 999999);
        $user->verification_code = $verification_code;
        $user->save();

  Mail::to($user->email)->send(new VerifyEmail($user));

      //return redirect('/get-started')->with('status', 'Your email has been verified.');
     return redirect('/email/verify');
    }
}
