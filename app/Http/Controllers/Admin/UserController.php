<?php //Texto de pruebaaaaaa

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        return view('admin.usuarios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validar que se cree bien
        $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        //Si pasa la validacion. creara el rol
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //Confitmacion de operacion exitosa
        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente',
        ]);

        //Redireccionara a la tabla principal
        return redirect(route('admin.usuarios.index'));//->with('success', 'User created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {  
        $roles = Role::all(); //Linea para obtener todos los roles

        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        //Validar que se inserte bien y que excluya la fila que se edita
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required'  
        ]);

        //Si pasa la validacion, editara el rol
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        //Actualizar role
        $usuario->syncRoles($request->role);

        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario actualizado',
            'text' => 'El usuario y su rol han sido modificados correctamente',
        ]);

        //Configuracion de operación exitosa
         session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido modificado correctamente',
        ]);

        //Redireccionada a la misma vista de editar
        return redirect(route('admin.usuarios.edit', $usuario));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // 1. EL CANDADO (Va primero). Verificamos si el ID del usuario coincide con el logueado.
        if ($usuario->id === Auth::user()->id) {
            abort(403, 'No puedes borrar tu propio usuario');
        }

        // 2. Si el código llega hasta aquí, significa que NO es él mismo. Procedemos a borrarlo.
        $usuario->delete();

        // 3. Mostramos la alerta de éxito
        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido borrado correctamente',
        ]);

        // 4. Redireccionamos a la tabla
        return redirect(route('admin.usuarios.index'));
     }
}
