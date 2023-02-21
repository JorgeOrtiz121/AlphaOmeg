<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $discarded_role_names = ['prisoner'];

    public function login(Request $request)
    {
        $request -> validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request['email'])->first();

      
        if (!$user || !$user->state || in_array($user->role->slug, $this->discarded_role_names) ||
            !Hash::check($request['password'], $user->password))
            {
                return $this->sendResponse(message: 'Credenciales obtenidas incorrectas.', code: 404);
            }

            if ($user->state!=1)
            {
                return $this->sendResponse(message: 'Esta cuenta esta bloqueda por algun motivo.', code: 404);
            }
            

      
        if (!$user->tokens->isEmpty())
        {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->sendResponse(message: 'Bienvenido usuario.', result: [
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function logout(Request $request)
    {
        
        $request->user()->tokens()->delete();

        return $this->sendResponse(message: 'Sesion Finalizada.');
    }


   
}
