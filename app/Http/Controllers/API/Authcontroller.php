<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class Authcontroller extends Controller
{
   /**
    * Logs in a user.
    *
    * @param LoginRequest $request The login request object.
    * @return \Illuminate\Http\JsonResponse The JSON response containing the login result.
    */
   public function login(LoginRequest $request)
   {
      // Validate the request data
      $validatedData = $request->validated();

      // Find the user by email
      $user = User::where('email', $validatedData['email'])->first();

      // If user does not exist, return error response
      if (!$user) {
         return response()->json(['message' => 'Invalid Email'], 401);
      } else {
         // Verify the password
         if (!password_verify($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Invalid Password'], 401);
         } else {
            // Create a new authentication token for the user
            $token = $user->createToken('authToken')->plainTextToken;

            // Return success response with token and user details
            return response()->json([
               'message' => 'success',
               'token' => $token,
               'user' => $user,
               'token_type' => 'Bearer',
            ], 200);
         }
      }
   }
   /**
    * Registers a new user.
    *
    * @param RegisterRequest $request The request object containing the user's registration data.
    *
    * @throws \Some_Exception_Class A description of the exception that can be thrown.
    *
    * @return \Illuminate\Http\JsonResponse The JSON response containing the success message, token, user data, and token type.
    */
   public function register(RegisterRequest $request)
   {
      // Validate the request data
      $validatedData = $request->validated();

      // Hash the user's password
      $validatedData['password'] = bcrypt($validatedData['password']);

      // Create a new user
      $user = User::create($validatedData);

      // Generate a new token for the user
      $token = $user->createToken('authToken')->plainTextToken;

      // Return the JSON response
      return response()->json([
         'message' => 'success',
         'token' => $token,
         'user' => $user,
         'token_type' => 'Bearer',
      ], 201);
   }
   public function logout(Request $request)
   {
      // Revoke the token that was used to authenticate the current request...
      $request->user()->currentAccessToken()->delete();
      return response()->json(['message' => 'Successfully logged out']);
   }
}
