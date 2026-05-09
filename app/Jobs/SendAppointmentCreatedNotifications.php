<?php

namespace App\Jobs;

use App\Mail\AppointmentCreatedMail;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentCreatedNotifications implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Generar PDF
        $pdf = Pdf::loadView('pdf.appointment', ['appointment' => $this->appointment]);
        $pdfContent = $pdf->output();

        // 2. Enviar Correo Electrónico
        $patientEmail = $this->appointment->patient->user->email;
        $doctorEmail = $this->appointment->doctor->user->email;

        Mail::to($patientEmail)
            ->cc($doctorEmail)
            ->send(new AppointmentCreatedMail($this->appointment, $pdfContent));

        // 3. Enviar Mensaje de WhatsApp
        $this->sendWhatsAppMessage();
    }

    protected function sendWhatsAppMessage()
    {
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');
        $countryCode = $this->appointment->patient->user->country_code;
        $patientPhone = $this->appointment->patient->user->phone;

        if (!$token || !$phoneId || !$patientPhone) {
            Log::warning('No se pudo enviar WhatsApp: Faltan credenciales o el paciente no tiene teléfono registrado.');
            return;
        }

        // Asegurarnos de que tenga un código de país por defecto si es nulo
        $countryCode = $countryCode ?? '52';

        // Formatear el número de teléfono (quitar caracteres no numéricos)
        $patientPhone = preg_replace('/[^0-9]/', '', $patientPhone);

        // Concatenar el código de país con el número
        $patientPhone = $countryCode . $patientPhone;

        Log::info("Intentando enviar WhatsApp al número: {$patientPhone}");

        $message = "Hola {$this->appointment->patient->user->name}, tu cita con el Dr(a). {$this->appointment->doctor->user->name} ha sido confirmada para el {$this->appointment->date->format('d/m/Y')} a las {$this->appointment->start_time}.";

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $patientPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

        if ($response->failed()) {
            Log::error('Error enviando mensaje de WhatsApp: ' . $response->body());
        }
    }
}
