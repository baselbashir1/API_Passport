<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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

        if (Auth::guard($request->segment(3))->attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::guard($request->segment(3))->user();
            dd($user);

            $token = $user->createToken('MyToken', [$request->segment(3)])->accessToken;
            return response()->json(['data' => $user, 'token' => $token]);
        }
    }
}
