<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Cita</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #2563eb; text-align: center;">Confirmación de Cita Médica</h2>
        <p>Hola <strong>{{ $appointment->patient->user->name }}</strong>,</p>
        <p>Tu cita ha sido agendada exitosamente en Healthify. Aquí tienes los detalles:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Doctor:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">Dr(a). {{ $appointment->doctor->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Especialidad:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $appointment->doctor->specialty }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Fecha:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $appointment->date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Hora:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $appointment->start_time }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Motivo:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $appointment->reason }}</td>
            </tr>
        </table>

        <p>Se ha adjuntado a este correo un comprobante en PDF con los detalles completos de tu cita.</p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ config('app.url') }}" style="background-color: #2563eb; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 5px;">Ir a mi cuenta</a>
        </p>

        <p style="font-size: 12px; color: #777; margin-top: 40px; text-align: center;">
            Gracias por confiar en Healthify.<br>
            Si tienes alguna duda, por favor contáctanos.
        </p>
    </div>
</body>
</html>
