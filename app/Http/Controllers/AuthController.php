<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      // 'name' => "Ali",
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return response(['The provided credentials are incorrect...XATO...']);
    }

    return response([
      'token' => $user->createToken($user->name)->plainTextToken
    ]);
  }

  public function register(Request $request)
  {
    $request->validate([
      'name' => 'required|max:255',
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken($user->name)->plainTextToken;

    return response([
      'user' => $user,
      'token' => $token,
    ]);
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response(
      'SUCCESS...logger out'
    );
  }
}