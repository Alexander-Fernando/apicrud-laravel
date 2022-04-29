<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    //Método para el registro
    public function register(Request $request)
    {
        //Criterios de validación del registro
        $dataValidated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($dataValidated->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'errors' => $dataValidated->errors()
                ],
                422
            );
        };

        //Obtención de los datos validados
        $validateData = $dataValidated->validated();

        //hashear password
        $validateData['password'] = bcrypt($validateData['password']);

        //Creando y guardando el nuevo usuario
        $user = User::create($validateData);


        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

    //método para el login
    public function login(Request $request)
    {
        // Establecer criterios de validación
        $dataValidated = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        //Responder en caso de error en la validación de los datos
        if ($dataValidated->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $dataValidated->errors()
            ], 422);
        }

        $validatedData = $dataValidated->validated();
        //Validación de credenciales
        if (!Auth()->attempt($validatedData)) {
            return response()->json([
                "status" => "error",
                "errors" => [
                    "credentials" => ["Credenciales incorrectas"]
                ]
            ], 401);
        }

        //Creación del token del usuario
        // $user = Auth::user();
        $userToken = Auth::user()->createToken('authToken')->accessToken;

        //Retornar las credenciales del usuario
        return response()->json([
            "status" => "success",
            "user" => Auth::user(),
            "token" => $userToken
        ], 200);
    }
}
