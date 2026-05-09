<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de WhatsApp para las citas que ocurrirán en exactamente 24 horas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Enviar recordatorios para dentro de 24 horas (1 día)
        $this->sendRemindersFor(24);
        
        // Enviar recordatorios para dentro de 2 horas
        $this->sendRemindersFor(2);
    }

    protected function sendRemindersFor($hoursAhead)
    {
        $targetTime = now()->addHours($hoursAhead);
        $targetDate = $targetTime->toDateString();
        $targetHour = $targetTime->format('H');

        $appointments = Appointment::whereDate('date', $targetDate)
            ->where('start_time', 'like', $targetHour . ':%')
            ->where('status', Appointment::STATUS_SCHEDULED)
            ->get();

        if ($appointments->isEmpty()) {
            return;
        }

        $count = 0;

        foreach ($appointments as $appointment) {
            $token = config('services.whatsapp.token');
            $phoneId = config('services.whatsapp.phone_id');
            $countryCode = $appointment->patient->user->country_code;
            $patientPhone = $appointment->patient->user->phone;

            if (!$token || !$phoneId || !$patientPhone) {
                continue;
            }

            // Asegurarnos de que tenga un código de país por defecto si es nulo
            $countryCode = $countryCode ?? '52';

            $patientPhone = preg_replace('/[^0-9]/', '', $patientPhone);

            // Concatenar el código de país con el número
            $patientPhone = $countryCode . $patientPhone;

            Log::info("Intentando enviar recordatorio WhatsApp al número: {$patientPhone}");

            // Personalizar el mensaje dependiendo de si es para hoy o mañana
            $timeText = $hoursAhead == 24 ? "mañana" : "hoy";
            $message = "Hola {$appointment->patient->user->name}, te recordamos que {$timeText} a las {$appointment->start_time} tienes una cita con el Dr(a). {$appointment->doctor->user->name}.";

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $patientPhone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                $count++;
            } else {
                Log::error("Error enviando recordatorio a {$patientPhone}: " . $response->body());
            }
        }

        $this->info("Se enviaron {$count} recordatorios de WhatsApp para citas en {$hoursAhead} horas.");
    }
}
