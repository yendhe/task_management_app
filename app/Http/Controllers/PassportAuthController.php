<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $this->validate($request, [
                'name' => 'required|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);
            // Create or update the user
            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]
            );

            // Generate token
            $token = $user->createToken('LaravelAuthApp')->accessToken;

            return response()->json(['token' => $token], 200);
        } catch (ValidationException $e) {
            // Validation failed
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
