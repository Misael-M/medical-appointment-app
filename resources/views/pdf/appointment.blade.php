<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Cita Médica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
        }
        .content {
            margin: 0 auto;
            width: 90%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .info-table th, .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .info-table th {
            background-color: #f4f4f9;
            width: 30%;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Healthify</h1>
        <p>Comprobante de Cita Médica</p>
    </div>

    <div class="content">
        <p>A continuación se presentan los detalles de la cita agendada:</p>

        <table class="info-table">
            <tr>
                <th>Paciente</th>
                <td>{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <th>Doctor(a)</th>
                <td>{{ $appointment->doctor->user->name }} ({{ $appointment->doctor->specialty }})</td>
            </tr>
            <tr>
                <th>Fecha de la Cita</th>
                <td>{{ $appointment->date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Hora</th>
                <td>{{ $appointment->start_time }} a {{ $appointment->end_time }}</td>
            </tr>
            <tr>
                <th>Motivo</th>
                <td>{{ $appointment->reason }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Este documento es un comprobante automático generado por el sistema Healthify.<br>
        Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
