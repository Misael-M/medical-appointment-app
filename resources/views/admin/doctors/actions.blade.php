<div class="flex items-center gap-x-2">

    {{-- Botón editar --}}
    <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Botón gestionar horario --}}
    <x-wire-button href="{{ route('admin.doctors.schedule', $doctor) }}" green xs>
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>

</div>
