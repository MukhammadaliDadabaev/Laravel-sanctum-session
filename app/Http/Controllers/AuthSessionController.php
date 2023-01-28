<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthSessionController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return response('SUCCESS...😎');
    }

    return response(['Do not SUCCESS...❌']);
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

    Auth::login($user);

    return response([
      'user' => $user,
    ]);
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return response(['Logged out... Tizimdan chiqdi..']);
  }
}