<div>
<div class="max-w-4xl mx-auto py-6 space-y-5">

    {{-- ── Header: Nombre del paciente + botones de historia ────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Nombre + DNI --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ $appointment->patient->user->name }}
                </h1>
                @if ($appointment->patient->user->id_number)
                    <p class="text-sm text-gray-500 mt-0.5">DNI: {{ $appointment->patient->user->id_number }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    Cita del {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                    · {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('H:i') }}
                    – {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('H:i') }}
                    · Dr. {{ $appointment->doctor->user->name }}
                </p>
            </div>

            {{-- Botones de historial --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Ver Historia Médica --}}
                <button wire:click="openHistoryModal"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                    <i class="fa-solid fa-file-medical text-indigo-500"></i>
                    Ver Historia
                </button>

                {{-- Consultas Anteriores --}}
                <button wire:click="openPreviousModal"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>
                    Consultas Anteriores
                </button>
            </div>
        </div>
    </div>

    {{-- ── Tabs + Contenido ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 px-6">
            <nav class="flex gap-0 -mb-px" aria-label="Tabs">

                {{-- Tab: Consulta --}}
                <button wire:click="setTab('consulta')"
                    class="inline-flex items-center gap-2 px-4 py-3.5 text-sm font-medium border-b-2 transition-all duration-150 focus:outline-none
                        {{ $activeTab === 'consulta'
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fa-solid fa-stethoscope"></i>
                    Consulta
                </button>

                {{-- Tab: Receta --}}
                <button wire:click="setTab('receta')"
                    class="inline-flex items-center gap-2 px-4 py-3.5 text-sm font-medium border-b-2 transition-all duration-150 focus:outline-none
                        {{ $activeTab === 'receta'
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                    Receta
                </button>

            </nav>
        </div>

        {{-- ── PANEL: Consulta ─────────────────────────────────────────── --}}
        @if ($activeTab === 'consulta')
        <div class="px-6 py-6 space-y-5">

            {{-- Diagnóstico --}}
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Diagnóstico <span class="text-red-500">*</span>
                </label>
                <textarea id="diagnosis" wire:model="diagnosis" rows="4"
                    placeholder="Describa el diagnóstico del paciente aquí..."
                    class="w-full rounded-xl border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none
                           @error('diagnosis') border-red-400 bg-red-50 @enderror"></textarea>
                @error('diagnosis')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tratamiento --}}
            <div>
                <label for="treatment" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tratamiento <span class="text-red-500">*</span>
                </label>
                <textarea id="treatment" wire:model="treatment" rows="4"
                    placeholder="Describa el tratamiento recomendado aquí..."
                    class="w-full rounded-xl border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none
                           @error('treatment') border-red-400 bg-red-50 @enderror"></textarea>
                @error('treatment')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notas --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Notas
                </label>
                <textarea id="notes" wire:model="notes" rows="3"
                    placeholder="Agregue notas adicionales sobre la consulta..."
                    class="w-full rounded-xl border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
            </div>

        </div>
        @endif

        {{-- ── PANEL: Receta ───────────────────────────────────────────── --}}
        @if ($activeTab === 'receta')
        <div class="px-6 py-6 space-y-4">

            {{-- Cabecera de columnas --}}
            @if (count($medications) > 0)
            <div class="grid grid-cols-12 gap-3 px-1">
                <div class="col-span-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Medicamento</div>
                <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosis</div>
                <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Frecuencia / Duración</div>
                <div class="col-span-1"></div>
            </div>
            @endif

            {{-- Filas de medicamentos --}}
            @foreach ($medications as $idx => $med)
            <div class="grid grid-cols-12 gap-3 items-center bg-gray-50 rounded-xl p-3 border border-gray-100">

                {{-- Nombre del medicamento --}}
                <div class="col-span-5">
                    <input type="text"
                        wire:model="medications.{{ $idx }}.name"
                        placeholder="Ej. Amoxicilina 500mg"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                </div>

                {{-- Dosis --}}
                <div class="col-span-3">
                    <input type="text"
                        wire:model="medications.{{ $idx }}.dose"
                        placeholder="1 cada 8 horas"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                </div>

                {{-- Frecuencia / Duración --}}
                <div class="col-span-3">
                    <input type="text"
                        wire:model="medications.{{ $idx }}.frequency"
                        placeholder="Ej. cada 8 horas por 7 días"
                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                </div>

                {{-- Eliminar --}}
                <div class="col-span-1 flex justify-center">
                    <button wire:click="removeMedication({{ $idx }})"
                        class="p-1.5 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-150">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>

            </div>
            @endforeach

            {{-- Botón añadir medicamento --}}
            <button wire:click="addMedication"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-dashed border-gray-300 text-sm font-medium text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition-all duration-150 w-full justify-center">
                <i class="fa-solid fa-plus"></i>
                Añadir Medicamento
            </button>

        </div>
        @endif

        {{-- ── Footer: Guardar ──────────────────────────────────────────── --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button wire:click="saveConsultation"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors duration-150 shadow-sm">
                <i class="fa-solid fa-floppy-disk"></i>
                Guardar Consulta
            </button>
        </div>

    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Historia Médica del Paciente                                 --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
@if ($showHistoryModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background: rgba(0,0,0,0.45);">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden"
         x-data x-transition>

        {{-- Header del modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-file-medical text-indigo-500"></i>
                Historia médica del paciente
            </h3>
            <button wire:click="closeHistoryModal"
                class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Contenido --}}
        <div class="px-6 py-5 space-y-4">

            {{-- Fila de datos médicos --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Tipo de sangre:</p>
                    <p class="text-sm font-bold text-gray-900">
                        {{ $appointment->patient->bloodType?->name ?? '—' }}
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Alergias:</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $appointment->patient->allergies ?? 'No registradas' }}
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Enfermedades crónicas:</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $appointment->patient->chronic_conditions ?? 'No registradas' }}
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Antecedentes quirúrgicos:</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $appointment->patient->surgical_history ?? 'No registrados' }}
                    </p>
                </div>

            </div>

            {{-- Enlace a historia completa --}}
            <div class="border-t border-gray-100 pt-4 flex justify-end">
                <a href="{{ route('admin.patients.edit', $appointment->patient) }}"
                   class="text-sm text-indigo-600 hover:underline font-medium">
                    Ver / Editar Historia Médica →
                </a>
            </div>

        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Consultas Anteriores                                         --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
@if ($showPreviousModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background: rgba(0,0,0,0.45);">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden"
         x-data x-transition>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i>
                Consultas Anteriores
            </h3>
            <button wire:click="closePreviousModal"
                class="p-1 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Contenido --}}
        <div class="px-6 py-5 max-h-[75vh] overflow-y-auto space-y-4 bg-gray-50">

            @forelse ($previousConsultations as $prev)
            <div class="bg-white rounded-xl border border-indigo-200 shadow-sm overflow-hidden p-5 relative">
                {{-- Header de la tarjeta --}}
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2 text-indigo-700 mb-1">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span class="font-bold text-[15px]">
                                {{ \Carbon\Carbon::parse($prev->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::createFromFormat('H:i:s', $prev->start_time)->format('H:i') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">
                            Atendido por: Dr(a). {{ $prev->doctor->user->name }}
                        </p>
                    </div>

                    <a href="{{ route('admin.appointments.show', $prev->id) }}" class="inline-block px-3 py-1.5 text-xs font-semibold text-indigo-600 border border-indigo-300 rounded-lg hover:bg-indigo-50 transition-colors">
                        Consultar Detalle
                    </a>
                </div>

                {{-- Contenido de diagnóstico y tratamiento --}}
                <div class="space-y-2 text-sm">
                    <p class="text-gray-700">
                        <span class="font-bold text-gray-900">Diagnóstico:</span>
                        {{ $prev->diagnosis ?? ($prev->reason ?? 'No registrado') }}
                    </p>
                    <p class="text-gray-700">
                        <span class="font-bold text-gray-900">Tratamiento:</span>
                        {{ $prev->treatment ?? 'No registrado' }}
                    </p>
                    @if(!empty($prev->notes))
                    <p class="text-gray-700">
                        <span class="font-bold text-gray-900">Notas:</span>
                        {{ $prev->notes }}
                    </p>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-gray-400">
                <i class="fa-solid fa-folder-open text-3xl mb-3 block"></i>
                <p class="text-sm">No hay consultas anteriores registradas para este paciente.</p>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endif

</div>
