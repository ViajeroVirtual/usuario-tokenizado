<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::limit(10)->get();
            return response()->json([
                'success' => true,
                'message' => 'Usuarios obtenidos correctamente',
                'data' => $users
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8'
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8']
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                    'data' => null
                ], 401);
            }

            $oldToken = Token::where('user_id', $user->id)->first();
            if ($oldToken) {
                $oldToken->delete();
            }

            $tokenString = hash('sha256', Str::random(64));
            $token = Token::create([
                'user_id' => $user->id,
                'token' => $tokenString
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => $tokenString
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null
            ]);
        }
    }

    public function updateName(Request $request)
    {
        try {
            $data = $request->validate([
                'token' => 'required|string',
                'name' => 'required|string|max:255'
            ]);

            $tokenRecord = Token::where('token', $data['token'])->first();

            if (!$tokenRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido',
                    'data' => null
                ], 401);
            }

            $user = $tokenRecord->user;
            $user->name = $data['name'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Nombre actualizado correctamente',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
