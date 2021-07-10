<?php

namespace Mymo\Backend\Http\Controllers\Auth;

use Mymo\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mymo\Core\Models\PasswordReset;
use Mymo\Core\Models\User;
use Mymo\Core\Traits\ResponseMessage;
use Mymo\Email\EmailService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use ResponseMessage;

    public function index()
    {
        do_action('auth.forgot-password.index');
        
        return view('mymo::auth.forgot_password', [
            'title' => trans('mymo::app.forgot_password')
        ]);
    }
    
    public function forgotPassword(Request $request)
    {
        do_action('auth.forgot-password.handle', $request);
        
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $email = $request->post('email');
        $user = User::whereEmail($email)->first();
    
        try {
            $resetToken = Str::random(32);
            PasswordReset::create([
                'email' => $request->post('email'),
                'token' => $resetToken,
            ]);
    
            EmailService::make()
                ->withTemplate('reset_password')
                ->setParams([
                    'name' => $user->name,
                    'email' => $email,
                    'token' => $resetToken,
                ])
                ->send();
            
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    
        return $this->success(['redirect' => route('auth.forgot-password')]);
    }
}
