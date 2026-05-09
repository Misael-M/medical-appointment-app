<?php

namespace App\Console\Commands;

use App\Mail\DailyReportMail;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía reportes diarios de citas al Administrador y a cada Doctor.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Obtener todas las citas programadas y completadas para hoy
        $appointments = Appointment::whereDate('date', today())
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_COMPLETED])
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('start_time')
            ->get();

        // 2. Enviar reporte general a los administradores
        $admins = User::role('Administrador')->get();
        
        // Si hay administradores, o si hay un admin configurado por defecto en .env
        $adminEmails = $admins->pluck('email')->toArray();
        if (empty($adminEmails) && config('mail.admin_address')) {
            $adminEmails = [config('mail.admin_address')];
        }

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new DailyReportMail($appointments, 'Reporte General de Citas'));
            $this->info('Reporte general enviado a los administradores.');
        } else {
            $this->warn('No se encontraron administradores para enviar el reporte general.');
        }

        // 3. Agrupar citas por doctor y enviar reportes individuales
        $groupedAppointments = $appointments->groupBy('doctor_id');

        foreach ($groupedAppointments as $doctorId => $doctorAppointments) {
            $doctorUser = $doctorAppointments->first()->doctor->user;
            
            if ($doctorUser && $doctorUser->email) {
                Mail::to($doctorUser->email)->send(new DailyReportMail($doctorAppointments, 'Reporte de Mis Citas'));
                $this->info("Reporte enviado al Dr(a). {$doctorUser->name}.");
                sleep(1); // Evitar límite de peticiones de Mailtrap (Too many emails per second)
            }
        }

        $this->info('Proceso de reportes diarios finalizado.');
    }
}
