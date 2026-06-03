<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diploma Ecotop</title>
    <style>
        @page { 
            size: A4 landscape; 
            margin: 0; 
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #e5e7eb;
            font-family: 'Helvetica', 'Arial', sans-serif;
            -webkit-print-color-adjust: exact;
        }
        .wrapper {
            position: absolute;
            top: 25px;
            bottom: 25px;
            left: 25px;
            right: 25px;
            background-color: #fcfbf8;
            border: 1px solid #c5a059;
            box-shadow: inset 0 0 0 10px #fcfbf8, inset 0 0 0 12px #c5a059;
            padding: 2px;
        }
        .inner-wrapper {
            position: absolute;
            top: 15px;
            bottom: 15px;
            left: 15px;
            right: 15px;
            border: 1px solid rgba(197, 160, 89, 0.3);
            text-align: center;
        }
        .corner-tl { position: absolute; top: 10px; left: 10px; font-size: 30px; color: #c5a059; }
        .corner-tr { position: absolute; top: 10px; right: 10px; font-size: 30px; color: #c5a059; }
        .corner-bl { position: absolute; bottom: 10px; left: 10px; font-size: 30px; color: #c5a059; }
        .corner-br { position: absolute; bottom: 10px; right: 10px; font-size: 30px; color: #c5a059; }
        
        .header-text {
            margin-top: 60px;
            color: #022c22;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 5px;
            text-transform: uppercase;
        }
        
        .main-title {
            font-family: 'Georgia', serif;
            font-size: 56px;
            color: #022c22;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 8px;
        }
        
        .sub-title {
            font-family: 'Georgia', serif;
            font-style: italic;
            font-size: 22px;
            color: #c5a059;
            margin-bottom: 50px;
        }

        .presented {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 30px;
        }
        
        .name {
            font-family: 'Georgia', serif;
            font-style: italic;
            font-size: 64px;
            color: #022c22;
            margin-bottom: 10px;
        }
        
        .line-gold {
            width: 60%;
            height: 2px;
            background-color: #c5a059;
            margin: 0 auto 30px auto;
        }

        .description {
            font-family: 'Georgia', serif;
            font-size: 18px;
            line-height: 1.8;
            color: #374151;
            max-width: 75%;
            margin: 0 auto 40px auto;
        }

        .badge-rank {
            display: inline-block;
            padding: 8px 30px;
            border-top: 1px solid #c5a059;
            border-bottom: 1px solid #c5a059;
            font-size: 15px;
            color: #c5a059;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 30px;
        }

        .footer-container {
            position: absolute;
            bottom: 50px;
            width: 100%;
        }

        .footer-table {
            width: 90%;
            margin: 0 auto;
        }

        .footer-table td {
            text-align: center;
            vertical-align: bottom;
            width: 33.33%;
        }

        .signature-line {
            width: 220px;
            border-bottom: 1px solid #022c22;
            margin: 0 auto 10px auto;
        }

        .signature-title {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .seal {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            position: relative;
        }
        
        .seal-outer {
            width: 100px;
            height: 100px;
            background-color: #c5a059;
            border-radius: 50px;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .seal-inner {
            width: 86px;
            height: 86px;
            border: 2px dashed #fcfbf8;
            border-radius: 43px;
            position: absolute;
            top: 5px;
            left: 5px;
        }

        .seal-text {
            color: #fcfbf8;
            font-family: 'Georgia', serif;
            font-size: 12px;
            position: absolute;
            top: 40px;
            width: 100px;
            text-align: center;
            font-weight: bold;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.03;
            font-size: 250px;
            font-family: 'Georgia', serif;
            color: #022c22;
            font-weight: bold;
            z-index: -1;
        }

        .id-date {
            position: absolute;
            top: 30px;
            right: 40px;
            text-align: right;
            font-size: 12px;
            color: #9ca3af;
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .id-date strong {
            color: #c5a059;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="inner-wrapper">
            <div class="corner-tl">&#x25A3;</div>
            <div class="corner-tr">&#x25A3;</div>
            <div class="corner-bl">&#x25A3;</div>
            <div class="corner-br">&#x25A3;</div>
            
            <div class="watermark">&#x269C;</div>

            <div class="id-date">
                <strong>ID:</strong> {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Emisión:</strong> {{ now()->format('d M, Y') }}
            </div>

            <div class="header-text">Programa Expedición Ecotop</div>
            
            <div class="main-title">Certificado de Excelencia</div>
            
            <div class="sub-title">Condecoración al Mérito Ambiental</div>

            <div class="presented">Se otorga el presente diploma a</div>
            
            <div class="name">{{ $user->name }}</div>
            <div class="line-gold"></div>
            
            <div class="description">
                Por haber completado con dedicación y destreza los desafíos de los biomas colombianos, 
                demostrando un excepcional compromiso y liderazgo en la conservación de nuestros ecosistemas.
            </div>

            <div class="badge-rank">
                Rango: {{ $title ?? 'Guardián del Ecosistema' }} &nbsp; &bull; &nbsp; Puntaje: {{ $totalScore }}
            </div>

            <div class="footer-container">
                <table class="footer-table" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <div class="signature-line"></div>
                            <div class="signature-title">Dirección General</div>
                        </td>
                        <td>
                            <div class="seal">
                                <div class="seal-outer">
                                    <div class="seal-inner"></div>
                                    <div class="seal-text">ECOTOP<br>{{ now()->format('Y') }}</div>
                                </div>
                            </div>
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
