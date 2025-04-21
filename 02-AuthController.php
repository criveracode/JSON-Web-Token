<?php

namespace App\Http\Controllers;


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validar los datos
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generar token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Usuario registrado con éxito',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al registrar usuario',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validar credenciales
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6'
            ]);

            // Intentar autenticar al usuario
            if (!$token = Auth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Correo o contraseña incorrectos'
                ], 401);
            }

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en el login',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function me()
    {
        try {
            // Intentar obtener el usuario autenticado
            if (!$user = Auth::user()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Token inválido o usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'message' => 'Usuario autenticado',
                'user' => $user
            ], 200);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token expirado, por favor inicie sesión nuevamente'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token inválido'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token no encontrado en la solicitud'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            // Obtener el token de la solicitud
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'No se encontró un token en la solicitud'
                ], 401);
            }

            // Invalidar el token
            JWTAuth::invalidate($token);

            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Token expirado, por favor inicie sesión nuevamente'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'No se pudo invalidar el token'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
