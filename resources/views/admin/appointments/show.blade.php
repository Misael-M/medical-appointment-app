<x-admin-layout title="Detalle de Consulta" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Detalle'],
]">

<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Encabezado --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-white text-lg font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-file-medical"></i>
                        Detalle de Consulta
                    </h2>
                    <p class="text-indigo-100 text-sm mt-0.5">Información clínica y receta médica</p>
                </div>
                <div class="bg-white/20 px-3 py-1 rounded-full text-white text-sm font-medium">
                    {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} 
                    ({{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }})
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Info del Paciente --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Paciente</h3>
                <p class="text-gray-900 font-medium">{{ $appointment->patient->user->name }}</p>
                <p class="text-gray-500 text-sm mt-1">DNI: {{ $appointment->patient->user->id_number ?? 'N/A' }}</p>
            </div>

            {{-- Info del Doctor --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Atendido por</h3>
                <p class="text-gray-900 font-medium">Dr(a). {{ $appointment->doctor->user->name }}</p>
                <p class="text-gray-500 text-sm mt-1">Especialidad: {{ $appointment->doctor->specialty ?? 'General' }}</p>
            </div>
        </div>
    </div>

    {{-- Datos de la Consulta --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-indigo-700 mb-2 border-b border-gray-100 pb-2">Motivo Inicial</h3>
            <p class="text-gray-700 text-sm">{{ $appointment->reason ?? 'No especificado al agendar.' }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-indigo-700 mb-2 border-b border-gray-100 pb-2">Diagnóstico Médico</h3>
            <p class="text-gray-700 text-sm">{{ $appointment->diagnosis ?? 'No registrado.' }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-indigo-700 mb-2 border-b border-gray-100 pb-2">Tratamiento e Indicaciones</h3>
            <p class="text-gray-700 text-sm">{{ $appointment->treatment ?? 'No registrado.' }}</p>
        </div>

        @if(!empty($appointment->notes))
        <div>
            <h3 class="text-sm font-semibold text-indigo-700 mb-2 border-b border-gray-100 pb-2">Notas Adicionales</h3>
            <p class="text-gray-700 text-sm">{{ $appointment->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Receta Médica --}}
    @if(!empty($appointment->medications) && count($appointment->medications) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-prescription-bottle-medical text-indigo-500"></i>
                Receta Médica Extendida
            </h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Medicamento</th>
                        <th class="px-6 py-3 font-medium">Dosis</th>
                        <th class="px-6 py-3 font-medium">Frecuencia / Duración</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($appointment->medications as $med)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $med['name'] ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $med['dose'] ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $med['frequency'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Botones --}}
    <div class="flex justify-end pt-2">
        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Volver a Citas
        </a>
    </div>

</div>

</x-admin-layout>
