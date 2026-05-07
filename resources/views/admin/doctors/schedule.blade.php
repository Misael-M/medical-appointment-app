<x-admin-layout title="Horarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Horarios']
]">

<div class="max-w-6xl mx-auto py-6">
    @livewire('admin.schedule-manager', ['doctor' => $doctor])
</div>

</x-admin-layout>
