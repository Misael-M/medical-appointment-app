<div class="flex items-center gap-x-2">

    {{-- Botón editar --}}
    <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Botón atender cita (Estetoscopio) --}}
    <x-wire-button href="{{ route('admin.appointments.consult', $appointment) }}" green xs>
        <i class="fa-solid fa-stethoscope"></i>
    </x-wire-button>

    {{-- Botón eliminar --}}
    <x-wire-button
        onclick="confirmDelete({{ $appointment->id }})"
        negative xs>
        <i class="fa-solid fa-trash"></i>
    </x-wire-button>

</div>
