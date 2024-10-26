<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logincustom(Request $request)
    {
        try {
            $credentials = $request->only('usuario', 'password');
            \Log::info('Credenciales antes de la autenticación manual:', $credentials);
    
            // Buscar usuario por nombre de usuario
            $user = User::where('usuario', $credentials['usuario'])->first();
    
            if (!$user) {
                throw ValidationException::withMessages([
                    'usuario' => [trans('auth.failed')],
                ]);
            }
    
            // Verificar la contraseña manualmente
            if (Hash::check($credentials['password'], $user->password)) {
                \Log::info('Contraseña verificada correctamente para el usuario:', ['usuario' => $user->usuario]);

                // Autenticación exitosa
                $empresa = DB::table('empresas')
                    ->join('users', 'empresas.empresa', '=', 'users.empresa')
                    ->where('users.usuario', $user->usuario)
                    ->select('empresas.razonsocialempresa', 'empresas.empresa')
                    ->first();
                //REDIRECCION MANUAL  
                  if (Auth::attempt($credentials)) {
                        // Autenticación exitosa
                        \Log::info('Empresa encontrada:', ['empresa' => $empresa]);
                        session(['nombre_usuario' => $user->name, 'razonsocialempresa' => $empresa->razonsocialempresa, 'empresa' => $empresa->empresa]);
                        return redirect()->route('menu');
                        
                    } 
                if ($empresa) {
                    \Log::info('Empresa encontrada:', ['empresa' => $empresa]);

                    session(['nombre_usuario' => $user->name, 'razonsocialempresa' => $empresa->razonsocialempresa, 'empresa' => $empresa->empresa]);
                } else {
                    \Log::warning('No se encontró la empresa para el usuario:', ['usuario' => $user->usuario]);
                }
                \Log::info('Redirigiendo al menú');
                return redirect()->route('menu');
            } else {
                \Log::warning('Contraseña incorrecta para el usuario:', ['usuario' => $credentials['usuario']]);
           
                // Contraseña incorrecta
                throw ValidationException::withMessages([
                    'usuario' => [trans('auth.failed')],
                ]);
            }
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            \Log::warning('Credenciales incorrectas:', ['credentials' => $credentials, 'error' => $errors]);
            return back()->withErrors($errors);
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'usuario' => 'required|unique:users,usuario',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ], [
                'usuario.unique' => 'El usuario ya está en uso.',
                'email.unique' => 'El correo electrónico ya está en uso.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()]);
            }

            $user = User::create([
                'name' => $request->input('name'),
                'usuario' => $request->input('usuario'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
            return response()->json(['success' => true, 'message' => 'Usuario registrado exitosamente']);

        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()]);
        }
    }

    public function showMenu()
    {
        return view('auth.menu');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('status', 'You have been logged out!');
    }

    public function __construct()
    {
        $this->middleware('auth')->only('showMenu');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function updateuser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()]);
        }
        try {
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()]);
            }

            // Obtén el usuario por nombre de usuario y correo electrónico
            $user = User::where('usuario', $request->input('usuario'))
                ->where('email', $request->input('email'))
                ->first();

            if (!$user) {
                throw new \Exception('No se encontró ningún usuario con las credenciales proporcionadas');
            }

            // Actualiza la contraseña
            $user->password = bcrypt($request->input('password'));
            $user->save();
            return response()->json(['success' => true, 'message' => 'Se actualizo la contraseña correctamente']);

        } catch (\Exception $e) {
            \Log::error('Error durante la actualización de la contraseña: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'message' => 'Error durante la actualización de la contraseña: ' . $e->getMessage()]);
        }
    }

    public function mostrarMantenimiento()
    {
        return view('usuarios');
    }

    public function listarDatosUsuarios()
    {
        $medidas = User::select(['id', 'name', 'usuario', 'estado'])->get();
        return response()->json($medidas);
    }

    public function guardarUsuarios(Request $request)
    {
        try {
            $request->validate([
                'usuario' => 'required',
                'nombres' => 'required',
                'telefono' => '',
                'correo' => '',
                'cargo' => '',
                'empresa' => '',

            ]);

            $almacen = new User;
            $almacen->usuario = $request->input('usuario');
            $almacen->name = $request->input('nombres');
            $almacen->telefono = $request->input('telefono');
            $almacen->email = $request->input('correo');
            $almacen->cargo = $request->has('cargo');
            $almacen->empresa = $request->has('empresa');
            $almacen->estado = $request->has('estado');

            // Guardar en la base de datos

            $almacen->save();
            $id = $almacen->id;

            return response()->json(['success' => true, 'id' => $id]);

        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarUsuarios(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'usuario' => 'required',
                'nombres' => 'required',
                'telefono' => '',
                'correo' => '',
                'cargo' => '',

            ]);

            $usuarios = User::find($id);
            // Verificar si el registro existe
            if (!$usuarios) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $usuarios->usuario = $request->input('usuario');
            $usuarios->name = $request->input('nombres');
            $usuarios->telefono = $request->input('telefono');
            $usuarios->email = $request->input('correo');
            $usuarios->cargo = $request->input('cargo');
            $usuarios->estado = $request->has('estado');
            $usuarios->save();
            $id = $usuarios->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsusuarios($id)
    {
        $datos = User::find($id);
        return response()->json($datos);
    }

    public function mostrarProfile()
    {
        return view('profile');
    }
}


