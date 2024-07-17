<?php

namespace App\Http\Controllers\API;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Password;
use App\Http\Controllers\API\BaseController;

class ForgotPasswordController extends BaseController
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset email sent'], 200)
            : response()->json(['message' => 'Unable to send password reset email'], 400);
    }
}
