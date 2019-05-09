<?php

namespace App\Http\Controllers\Auth;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Login.
     */
    public function login(Request $request) {
        $creds = $request->only('email', 'password');

        if( $token = $this->guard()->attempt($creds) ) :
            return response()->json([
                'status' => 'success',
            ], 200)->header('Authorization', $token);
        endif;
        
        return response()->json([
            'error'    => 'login-error',
        ], 401);
    }

    /**
     * Register
     */
    public function register(Request $request) {
        $data = $request->only(['name', 'email', 'password', 'type',]);
        $data['role'] = $request->type;
        $data['password'] = Hash::make($data['password']);
        $meta = json_decode($request->meta, TRUE);

        $user = User::create($data);
       
        foreach( $meta as $key => $value ) :
            $user->metas()->create([
                'key'   => $key,
                'val'   => is_array($value) ? serialize($value) : $value,
            ]);
        endforeach;

        $user->records()->create([
            'type'  => 'info',
            'date'  => date('Y-m-d', strtotime('now')),
            'data'  => $request->records
        ]);
        
        return response()->json($user);
    }

    /**
     * Logout.
     */
    public function logout() {
        $this->guard()->logout();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    /**
     * User
     */
    public function user(Request $request) {
        $user = User::find(Auth::user()->id);

        return response()->json([
            'status'    => 'success',
            'data'      => $user,
        ]);
    }

    /**
     * Refresh token.
     */
    public function refresh() {
        if( $token = $this->guard()->refresh() ) :
            return response()->json([
                'status'    => 'success',
            ], 200)->header('Authorization', $token);
        endif;

        return response()->json([
            'error'     => 'refresh-token-error',
        ], 401);
    }

    /**
     * Guard.
     */
    private function guard() {
        return Auth::guard();
    }
}
