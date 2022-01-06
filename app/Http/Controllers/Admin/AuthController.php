<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function login()
    {
        if(is_logged_in()) {
            return redirect('dashboard');
        }
        set_page_title('Login to your account');
        return view('admin.login');
    }

    /**
     * Process login
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // make sure user is active
        $credentials['status'] = 'active';

        $remember_me = $request->input('remember_me');

        if( Auth::attempt($credentials,boolval($remember_me)) )
        {
            $user = Auth::user();

            $greetings = [
                'Greetings <b>'.$user->first_name.'</b>',
                'Howdy <b>'.$user->first_name.'</b> have a good one.',
                'Welcome back, have a spiffing day <b>'.$user->first_name.'</b>',
                'Be awesome and sell some s*@!t <b>'.$user->first_name.'</b>',
                'Have a great day <b>'.$user->first_name.'</b>',
                'Yo Yo it\'s good to see you <b>'.$user->first_name.'</b>',
            ];

            // check intended url
            /*if(!session()->get('url.intended')) {
                return redirect(url('dashboard'))->with('success',$greetings[array_rand($greetings,1)]);
            }*/

            return redirect()->intended('dashboard')->with('success',$greetings[array_rand($greetings,1)]);
        }

        $error = 'Your login details are incorrect';
        // get user
        $user = User::where('email', $credentials['email'])->first();

        // check if user is suspended
        if( $user && $user->count() )
        {
            if( $user->status == 'suspended' && $user->notes )
            {
                $error = '<b>Suspended -</b> Your account is suspended the following note was left : '.$user->notes;
            }
        }

        return back()->withInput()->with('error', $error)->withInput();
    }

    /**
     * Logout user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        if(Auth::logout()) {
            return redirect('/')->with('success','You have successfully logged out');
        }

        return back()->with('error','There was an issue signing you out.');
    }
}
