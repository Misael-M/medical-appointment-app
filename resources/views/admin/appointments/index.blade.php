<x-admin-layout title="Citas Médicas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
    ],
]">
    <x-slot name="action">
        <a href="{{ route('admin.appointments.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors duration-150 shadow-sm">
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </a>
    </x-slot>

    @livewire('admin.datatables.appointment-table')

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer y la cita será eliminada permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // indigo-600
                cancelButtonColor: '#ef4444', // red-500
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteAppointment', { id: id });
                }
            })
        }
    </script>
</x-admin-layout>
