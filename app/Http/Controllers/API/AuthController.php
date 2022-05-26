<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|max:55",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
        ]);

        if ($validator->fails()) {
            return response(["error" => $validator->errors()]);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $accessToken = $user->createToken("authToken")->accessToken;

        return response(["user" => $user, "access_token" => $accessToken]);
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "email" => "required|email",
            "password" => "required|min:6",
        ]);

        if ($validator->fails()) {
            return response(["error" => $validator->errors()], 401);
        }

        if (!auth()->attempt($data)) {
            return response(["message" => "Login credentials are invalid"]);
        }

        $accessToken = auth()
            ->user()
            ->createToken("authToken")->accessToken;

        return response(["access_token" => $accessToken]);
    }
}
