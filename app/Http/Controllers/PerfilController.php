<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return \view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el Request
        $request->request->add(['username' => Str::slug( $request->username )]);

        if ($request->password ==! '')
        {
            $this->validate($request, [
                //Se recomienda utilizar un array cuando son mas de tres parámetros
                'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter,editar-perfil'],
                'email' => ['required', 'unique:users,email,'.auth()->user()->id, 'email', 'max:60'],
                'password' => 'confirmed|min:6',
            ]);
        }
        else
        {
            $this->validate($request, [
                //Se recomienda utilizar un array cuando son mas de tres parámetros
                'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter,editar-perfil'],
                'email' => ['required', 'unique:users,email,'.auth()->user()->id, 'email', 'max:60'],
            ]);
        }

        if ($request->imagen)
        {
            $imagen = $request->file('imagen');
    
            // Creamos el nombre de la imagen
            $nombreImagen = Str::uuid() . "." . $imagen->extension();
    
            // Convertimos la imagen en un cuadrado de 1000 x 1000
            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);
    
            // Guardamos la imagen en el servidor
            $imagenPath = \public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }
        
        // Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->imagen = $nombreImagen ?? \auth()->user()->imagen ?? null;//nombreImagen o imagen en BBDD o vacío si no se añade
        if ($request->password ==! '') $usuario->password = $request->password;
        $usuario->save();

        //Redireccionar
        return \redirect()->route('posts.index', $usuario->username);
    }
}
