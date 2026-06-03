<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diploma Ecotop</title>
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .outer-border {
            position: relative;
            margin: 40px;
            padding: 10px;
            border: 2px solid #059669;
            background-color: #ffffff;
            height: 900px;
        }
        .inner-border {
            position: relative;
            border: 4px double #10b981;
            padding: 50px;
            height: 792px;
            background-color: #ffffff;
            /* Dompdf linear-gradient support is limited, so we use a very light solid color or fallback */
            background: #fcfdfd; 
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 20px;
        }
        .logo {
            font-size: 26px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 50px;
        }
        .title {
            font-size: 52px;
            font-weight: bold;
            color: #064e3b;
            margin-bottom: 15px;
            font-family: 'Georgia', serif;
        }
        .subtitle {
            font-size: 22px;
            color: #059669;
            margin-bottom: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            text-align: center;
        }
        .presented-to {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .name {
            font-size: 46px;
            font-weight: bold;
            color: #111827;
            border-bottom: 2px solid #d1d5db;
            display: inline-block;
            padding: 0 40px 10px;
            margin-bottom: 40px;
            font-family: 'Georgia', serif;
        }
        .description {
            font-size: 20px;
            line-height: 1.6;
            color: #374151;
            max-width: 80%;
            margin: 0 auto 50px;
        }
        .funny-title {
            font-size: 24px;
            font-weight: bold;
            color: #047857;
            background-color: #d1fae5;
            display: inline-block;
            padding: 15px 30px;
            border: 2px solid #34d399;
            margin-bottom: 40px;
        }
        .stats {
            font-size: 22px;
            color: #4b5563;
            margin-bottom: 60px;
        }
        .footer {
            width: 100%;
            position: absolute;
            bottom: 80px;
            left: 0;
            text-align: center;
        }
        .signature-table {
            width: 80%;
            margin: 0 auto;
        }
        .signature-table td {
            text-align: center;
            vertical-align: bottom;
            width: 50%;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #4b5563;
            margin: 0 auto 10px;
        }
        .signature-title {
            font-size: 16px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .date-badge {
            position: absolute;
            top: 50px;
            right: 50px;
            text-align: right;
            font-size: 14px;
            color: #9ca3af;
        }
        .date-badge strong {
            color: #4b5563;
        }
        .watermark {
            position: absolute;
            top: 300px;
            left: 200px;
            opacity: 0.03;
            font-size: 200px;
            color: #059669;
            font-weight: bold;
            transform: rotate(-45deg);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="outer-border">
        <div class="inner-border">
            
            <div class="watermark">ECOTOP</div>

            <div class="date-badge">
                <strong>ID:</strong> {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Fecha:</strong> {{ now()->format('d M, Y') }}
            </div>
            
            <div class="header">
                <div class="logo">Expedición Ecotop</div>
                <div class="title">Certificado de Excelencia</div>
                <div class="subtitle">Galardón al Mérito Ambiental</div>
            </div>

            <div class="content">
                <div class="presented-to">Se otorga el presente diploma a</div>
                <div class="name">{{ $user->name }}</div>
                
                <div class="description">
                    Por haber superado con dedicación y astucia los desafíos de los biomas colombianos, demostrando un excepcional dominio sobre nuestros ecosistemas.
                </div>

                <div class="funny-title">
                    Rango Oficial: {{ $title ?? 'Guardián del Ecosistema' }}
                </div>

                <div class="stats">
                    <strong>Puntaje Final:</strong> {{ $totalScore }} puntos
                </div>
            </div>

            <div class="footer">
                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="signature-line"></div>
                            <div class="signature-title">Dirección de Expedición</div>
                        </td>
                        <td>
                            <div class="signature-line"></div>
                            <div class="signature-title">Comité Evaluador</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
