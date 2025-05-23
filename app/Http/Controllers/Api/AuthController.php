<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorUser;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $credentials = $req->only('email','password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error'=>'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
{
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_users',
            'username' => 'required|string|unique:vendor_users',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);
        $member = VendorUser::create($data);

        $token = auth('api')->login($member);
        return response()->json([ 'access_token' => $token, 'expires_in' => auth('api')->factory()->getTTL()*60 ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}