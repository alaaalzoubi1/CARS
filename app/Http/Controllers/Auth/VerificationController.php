<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\VerificationToken;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found.'],
                404);
        }
        $token = Str::random(6);
        $email = $request->email;

        VerificationToken::updateOrCreate(
            ['email' => $email],
            ['token' => $token]
        );

        Mail::raw("Your verification code is: $token", function ($message) use ($email) {
            $message->to($email)
                ->subject('Password Reset Verification Code');
        });

        return response()->json(['message' => 'Verification code sent successfully.']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $verification = VerificationToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

// Check if the token has expired (e.g., valid for 10 minutes)
        if (Carbon::now()->diffInMinutes($verification->created_at) > 100) {
            return response()->json(['message' => 'Verification code expired.'], 400);
        }
        $user = User::where('email', $request->email)
            ->first();
        if (!$user)
        {
            return response()->json(['message' => 'Email not found.'], 404);
        }
        $token = JWTAuth::fromUser($user);
        if (!$token) {
            return response()->json(['success' => false, 'error' => 'Invalid credentials'], 401);
        }
        $verification->delete();
        return response()->json(['token' => $token]);

    }
}
