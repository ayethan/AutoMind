<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User; // Make sure to import App\User

class AuthController extends Controller
{

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        
        // Manually find user and check password for debugging
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized - manual check failed'
            ], 401);
        }

        // Log the user in
        Auth::login($user, $request->remember_me);
        $token = $user->createToken('Personal Access Token')->accessToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    /**
     * 
     */
    public function changePassword(Request $request) {
        $this->validate($request, [
            "old_password" => "required",
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);   

        $user = auth()->user();

        if(!Hash::check($request->old_password, $user->password)) {
            abort(400);
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return response(null, 202);
    }


    public function updateProfile(Request $request) {
        $this->validate($request, [
            "name" => "required",
            "email" => "required|email"
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(202);
    }
}
