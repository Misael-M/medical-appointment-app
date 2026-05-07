<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    // Tab activo
    public string $activeTab = 'consulta';

    // Campos de Consulta
    public string $diagnosis  = '';
    public string $treatment  = '';
    public string $notes      = '';

    // Medicamentos (Receta)
    public array $medications = [];

    // Control del modal de historia
    public bool $showHistoryModal  = false;
    public bool $showPreviousModal = false;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment->load(['patient.user', 'patient.bloodType', 'doctor.user']);
        $this->addMedication(); // Empezar con una fila vacía
    }

    // ── Tabs ──────────────────────────────────────────
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // ── Receta ─────────────────────────────────────────
    public function addMedication(): void
    {
        $this->medications[] = [
            'name'      => '',
            'dose'      => '',
            'frequency' => '',
        ];
    }

    public function removeMedication(int $index): void
    {
        array_splice($this->medications, $index, 1);
    }

    // ── Modales ────────────────────────────────────────
    public function openHistoryModal(): void
    {
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal(): void
    {
        $this->showHistoryModal = false;
    }

    public function openPreviousModal(): void
    {
        $this->showPreviousModal = true;
    }

    public function closePreviousModal(): void
    {
        $this->showPreviousModal = false;
    }

    // ── Guardar ────────────────────────────────────────
    public function saveConsultation(): void
    {
        try {
            $this->validate([
                'diagnosis' => 'required|string|min:3',
                'treatment' => 'required|string|min:3',
            ], [
                'diagnosis.required' => 'El diagnóstico es obligatorio.',
                'treatment.required' => 'El tratamiento es obligatorio.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setTab('consulta');
            throw $e;
        }

        // Limpiar filas vacías de medicamentos
        $cleanMedications = array_filter($this->medications, function($med) {
            return !empty(trim($med['name']));
        });

        // Actualizamos el estado de la cita a "Completado" y guardamos los datos clínicos
        $this->appointment->update([
            'status' => 2,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'medications' => array_values($cleanMedications),
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Consulta guardada!',
            'text'  => 'La consulta ha sido registrada exitosamente.',
        ]);

        $this->redirectRoute('admin.appointments.index');
    }

    public function render()
    {
        // Consultas anteriores: todas las citas completadas del mismo paciente (incluyendo la actual si ya fue completada)
        $previousConsultations = Appointment::where('patient_id', $this->appointment->patient_id)
            ->where('status', 2)
            ->with(['doctor.user'])
            ->orderByDesc('date')
            ->get();

        return view('livewire.admin.consultation-manager', [
            'previousConsultations' => $previousConsultations,
        ]);
    }
}
