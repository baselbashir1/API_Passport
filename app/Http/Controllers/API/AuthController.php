<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Client\ResponseSequence;

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

            $token = $user->createToken('MyToken', [$request->segment(3)])->accessToken;
            return response()->json(['data' => $user, 'token' => $token]);
        }
    }

    public function userDetails(Request $request)
    {
        $user = Auth::user();
        $token = $request->bearerToken();

        return response()->json(['data' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json(['success' => 'Logout Successfully.']);
    }
}
