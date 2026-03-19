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
                <x-wire-input label="Nombre" name="name" placeholder="Nombre completo" 
                    value="{{ old('name') }}"></x-wire-input>

                <x-wire-input label="Correo Electrónico" name="email" type="email" placeholder="ejemplo@correo.com" 
                    value="{{ old('email') }}"></x-wire-input>

                <x-wire-input label="Contraseña" name="password" type="password" placeholder="Mínimo 8 caracteres"></x-wire-input>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" primary>Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>