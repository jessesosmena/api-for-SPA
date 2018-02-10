<?php 
namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

//use Jrean\UserVerification\Traits\VerifiesUsers;
//use Jrean\UserVerification\Facades\UserVerification;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    
    use RegistersUsers;
    //use VerifiesUsers;

   /**
    * Create a new controller instance.
    *
    * @return void
    */

    /*
    public function __construct()
    {
        // Based on the workflow you need, you may update and customize the following lines.

        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
    }
    */

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return IlluminateContractsValidationValidator
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return User
    */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => str_random(10),
        ]);
    }

    /**
    * Handle a registration request for the application.
    *
    * @param  IlluminateHttpRequest  $request
    * @return IlluminateHttpResponse
    */

    public function register(Request $request)
    {

    // Laravel validation 
    $validator = $this->validator($request->all()); 
  
    if ($validator->fails())  
    { 
        $this->throwValidationException($request, $validator); 
    } 
  
     // Using database transactions is useful here because stuff happening is actually a transaction 
    DB::beginTransaction(); 
    try 
    { 
        $user = $this->create($request->all()); 
  
         // After creating the user send an email with the random token generated in the create method above 
        $email = new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name])); 
  
        Mail::to($user->email)->send($email); 
  
        DB::commit(); 
        return back(); 
    } 
    catch(Exception $e) 
    { 
        DB::rollback();  
        return back(); 
    } 

    }

    // Get the user who has the same token and change his/her status to verified i.e. 1 
 
    public function verify($token) 
    { 
         // The verified method has been added to the user model and chained here 
         // for better readability 
         User::where('email_token',$token)->firstOrFail()->verified(); 
     
         return redirect('login'); 
    } 

}