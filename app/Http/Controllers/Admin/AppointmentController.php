<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Muestra el listado de citas.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Muestra el formulario de creación de citas.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors  = Doctor::with('user')->get();

        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Guarda una nueva cita en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'duration'   => 'nullable|integer|min:5',
            'reason'     => 'required|string|max:1000',
            'status'     => 'nullable|integer|in:1,2,3',
        ], [
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'patient_id.exists'   => 'El paciente seleccionado no existe.',
            'doctor_id.required'  => 'Debe seleccionar un doctor.',
            'doctor_id.exists'    => 'El doctor seleccionado no existe.',
            'date.required'       => 'La fecha es obligatoria.',
            'date.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'end_time.required'   => 'La hora de fin es obligatoria.',
            'end_time.after'      => 'La hora de fin debe ser posterior a la hora de inicio.',
            'reason.required'     => 'El motivo de la cita es obligatorio.',
        ]);

        // Calcular duración si no fue enviada
        if (empty($validated['duration'])) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
            $end   = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
            $validated['duration'] = (int) $start->diffInMinutes($end);
        }

        $validated['status'] = $validated['status'] ?? 1;

        Appointment::create($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Cita registrada!',
            'text'  => 'La cita ha sido agendada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Muestra el formulario para editar una cita.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors  = Doctor::with('user')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Actualiza la cita en la base de datos.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date', // quitamos after_or_equal:today por si están editando una cita pasada
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'duration'   => 'nullable|integer|min:5',
            'reason'     => 'required|string|max:1000',
            'status'     => 'nullable|integer|in:1,2,3',
        ], [
            'reason.required' => 'El motivo de la cita es obligatorio.',
        ]);

        // Calcular duración si no fue enviada
        if (empty($validated['duration'])) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
            $end   = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
            $validated['duration'] = (int) $start->diffInMinutes($end);
        }

        $appointment->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita actualizada',
            'text'  => 'La cita ha sido modificada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Muestra los detalles completos de una cita (incluyendo diagnóstico y receta).
     */
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Muestra la vista para atender la cita (Consultation Manager).
     */
    public function consult(Appointment $appointment)
    {
        return view('admin.appointments.consult', compact('appointment'));
    }
}
