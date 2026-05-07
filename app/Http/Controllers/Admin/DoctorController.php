<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Muestra el listado de doctores.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Muestra el formulario para editar datos del doctor.
     */
    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Actualiza la especialidad y número de licencia.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'specialty'      => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ]);

        $doctor->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor actualizado',
            'text'  => 'Los datos médicos del doctor han sido actualizados exitosamente.',
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Muestra el gestor de horarios del doctor.
     */
    public function schedule(Doctor $doctor)
    {
        return view('admin.doctors.schedule', compact('doctor'));
    }
}
