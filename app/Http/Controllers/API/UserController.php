<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userLogin(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()], 422);
        }

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user();
            $token = $user->createToken('MyToken')->accessToken;

            return response()->json(['data' => $user, 'token' => $token]);
        }
    }

    public function userDetails(Request $request)
    {
        $user = Auth::guard('api')->user();
        $token = $request->bearerToken();
        return response()->json(['data' => $user, 'token' => $token]);
    }
}
