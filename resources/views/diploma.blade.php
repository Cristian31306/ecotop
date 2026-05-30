<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diploma Ecotop</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background-color: #f9fafb; color: #111827; }
        .diploma-container { border: 10px solid #10b981; padding: 40px; background-color: #ffffff; border-radius: 10px; }
        h1 { font-size: 40px; color: #10b981; margin-bottom: 10px; }
        h2 { font-size: 24px; font-weight: normal; margin-bottom: 40px; }
        .name { font-size: 36px; font-weight: bold; margin: 20px 0; border-bottom: 2px solid #e5e7eb; display: inline-block; padding-bottom: 10px;}
        .score { font-size: 20px; margin-top: 30px; color: #4b5563; }
        .date { font-size: 16px; margin-top: 40px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="diploma-container">
        <h1>Certificado de Excelencia</h1>
        <h2>Por haber completado exitosamente la expedición de Ecotop</h2>
        <p>Este diploma es otorgado a:</p>
        <div class="name">{{ $user->name }}</div>
        <div class="score">Puntaje Total: {{ $totalScore }} puntos</div>
        <div class="date">Otorgado el {{ now()->format('d/m/Y') }}</div>
    </div>
</body>
</html>
