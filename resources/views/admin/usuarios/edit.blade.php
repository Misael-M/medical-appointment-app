<x-admin-layout title="Editar Usuario" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.usuarios.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">

    <x-wire-card>
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <x-wire-input label="Nombre" name="name" placeholder="Nombre del usuario" 
                    value="{{ old('name', $usuario->name) }}"></x-wire-input>

                <x-wire-input label="Correo Electrónico" name="email" type="email" placeholder="ejemplo@correo.com" 
                    value="{{ old('email', $usuario->email) }}"></x-wire-input>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol del Usuario</label>
                    <select name="role" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="" disabled {{ !$usuario->roles->count() ? 'selected' : '' }}>Selecciona un rol...</option>
                        
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ $usuario->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-wire-button type="submit" primary>Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>