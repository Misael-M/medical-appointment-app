<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Paciente', 'patient.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Doctor', 'doctor.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y')),

            Column::make('Hora Inicio', 'start_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::createFromFormat('H:i:s', $value)->format('H:i')),

            Column::make('Hora Fin', 'end_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::createFromFormat('H:i:s', $value)->format('H:i')),

            Column::make('Estado', 'status')
                ->sortable()
                ->label(function ($row) {
                    $colors = [
                        1 => 'bg-blue-100 text-blue-800',
                        2 => 'bg-green-100 text-green-800',
                        3 => 'bg-red-100 text-red-800',
                    ];
                    $labels = [
                        1 => 'Programado',
                        2 => 'Completado',
                        3 => 'Cancelado',
                    ];
                    $color = $colors[$row->status] ?? 'bg-gray-100 text-gray-700';
                    $label = $labels[$row->status] ?? 'Desconocido';
                    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$color}\">{$label}</span>";
                })
                ->html(),

            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                }),
        ];
    }

    #[\Livewire\Attributes\On('deleteAppointment')]
    public function delete($id)
    {
        Appointment::destroy($id);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Cita eliminada!',
            'text'  => 'La cita médica ha sido eliminada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }
}
