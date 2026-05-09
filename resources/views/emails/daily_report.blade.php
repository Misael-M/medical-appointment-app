<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; color: #333;">
    <div style="max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #2563eb; text-align: center;">{{ $reportTitle }}</h2>
        <p style="text-align: center;">Fecha: <strong>{{ now()->format('d/m/Y') }}</strong></p>
        
        @if($appointments->count() > 0)
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left;">Hora</th>
                        <th style="padding: 12px; text-align: left;">Paciente</th>
                        <th style="padding: 12px; text-align: left;">Doctor</th>
                        <th style="padding: 12px; text-align: left;">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;">{{ $appointment->start_time }}</td>
                        <td style="padding: 12px;">{{ $appointment->patient->user->name }}</td>
                        <td style="padding: 12px;">{{ $appointment->doctor->user->name }}</td>
                        <td style="padding: 12px;">{{ $appointment->reason }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="text-align: center; margin-top: 20px;">Total de citas: <strong>{{ $appointments->count() }}</strong></p>
        @else
            <div style="text-align: center; padding: 40px; background-color: #f8fafc; border-radius: 8px; margin-top: 20px;">
                <p style="color: #64748b; font-size: 16px;">No hay citas agendadas para el día de hoy.</p>
            </div>
        @endif

        <p style="font-size: 12px; color: #777; margin-top: 40px; text-align: center;">
            Este es un correo generado automáticamente por Healthify.
        </p>
    </div>
</body>
</html>
