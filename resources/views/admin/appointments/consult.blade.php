<x-admin-layout title="Atender Cita" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Consulta'],
]">

    @livewire('admin.consultation-manager', ['appointment' => $appointment])

</x-admin-layout>
