<x-admin-layout title="Editar Cita" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas',     'href' => route('admin.appointments.index')],
    ['name' => 'Editar'],
]">

<div class="max-w-3xl mx-auto py-6">

    {{-- Card principal --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5">
            <h2 class="text-white text-lg font-semibold flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Cita Médica
            </h2>
            <p class="text-indigo-100 text-sm mt-0.5">Modifique los datos de la cita seleccionada.</p>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Errores globales --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex gap-2 mb-1">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <p class="text-red-700 text-sm font-semibold">Por favor corrige los siguientes errores:</p>
                    </div>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-0.5 ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Sección: Paciente y Doctor --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                    Participantes
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Paciente --}}
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Paciente <span class="text-red-500">*</span>
                        </label>
                        <select id="patient_id" name="patient_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                   @error('patient_id') border-red-400 bg-red-50 @enderror">
                            <option value="">— Seleccionar paciente —</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}"
                                    {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }}
                                    {{ $patient->user->id_number ? '(DNI: '.$patient->user->id_number.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Doctor --}}
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Doctor <span class="text-red-500">*</span>
                        </label>
                        <select id="doctor_id" name="doctor_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                   @error('doctor_id') border-red-400 bg-red-50 @enderror">
                            <option value="">— Seleccionar doctor —</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name }}
                                    {{ $doctor->specialty ? '— '.$doctor->specialty : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Sección: Fecha y Horario --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                    Fecha y Horario
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">

                    {{-- Fecha --}}
                    <div class="sm:col-span-2">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" name="date"
                            value="{{ old('date', $appointment->date) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                   @error('date') border-red-400 bg-red-50 @enderror">
                        @error('date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hora Inicio --}}
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Inicio <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                   @error('start_time') border-red-400 bg-red-50 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hora Fin --}}
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500
                                   @error('end_time') border-red-400 bg-red-50 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Sección: Motivo y Estado --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                    Detalles y Estado
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="sm:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Motivo de la Cita <span class="text-red-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="3"
                            placeholder="Describa el motivo de la consulta..."
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none
                                   @error('reason') border-red-400 bg-red-50 @enderror">{{ old('reason', $appointment->reason) }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Estado
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ old('status', $appointment->status) == 1 ? 'selected' : '' }}>Programado</option>
                            <option value="2" {{ old('status', $appointment->status) == 2 ? 'selected' : '' }}>Completado</option>
                            <option value="3" {{ old('status', $appointment->status) == 3 ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.appointments.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                    <i class="fa-solid fa-xmark"></i>
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors duration-150 shadow-sm">
                    <i class="fa-solid fa-save"></i>
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>

</x-admin-layout>
