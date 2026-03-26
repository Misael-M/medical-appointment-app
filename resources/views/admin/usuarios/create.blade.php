<x-admin-layout title="Nombres" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.usuarios.index'),
    ],
    [
        'name' => 'Crear',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                <x-wire-input label="Nombre" name="name" placeholder="Nombre completo" 
                    value="{{ old('name') }}"></x-wire-input>

                <x-wire-input label="Correo Electrónico" name="email" type="email" placeholder="ejemplo@correo.com" 
                    value="{{ old('email') }}"></x-wire-input>

                <x-wire-input label="Contraseña" name="password" type="password" placeholder="Minimo 8 caracteres" required
                    autocomplete="New password"></x-wire-input>

                <x-wire-input label="Confirmar contraseña" name="password_confirmation" type="password" placeholder="Minimo 8 caracteres" required
                    autocomplete="New password"></x-wire-input>

                <x-wire-input label="Numero de id" name="id_number" placeholder="Ej. 123456789" autocomplete="off" 
                required inputmode="numeric" :value="old('id_number')"></x-wire-input>

                <x-wire-input label="Telefono" name="phone" placeholder="Ej. 9999999999" autocomplete="Tel" 
                required inputmode="Tel" :value="old('phone')"></x-wire-input>

                </div>

                <x-wire-input name="address" label="Direccion" required :value="old('address')"
                placeholder="Eje. calle 5..." autocomplete="street_address"></x-wire-input>
            </div>

            <div class="space-y-1">
                <x-wire-native-select name="role_id" label="Rol" required>
                    <option value="">
                        Seleccione un rol
                    </option>

                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                    {{ $role->name }}</option>
                @endforeach

                </x-wire-native-select>
                <p class="texte-sm text-gray-500">
                   Define los permisos y accesos del usuario  
                </p>

            </div>

            <div class="flex justify-end">
                <x-wire-button type="submit" primary>Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>