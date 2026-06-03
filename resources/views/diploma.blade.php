<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Diploma Ecotop</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;700&display=swap');

@page { 
    size: A4 landscape; 
    margin: 0; 
}
body {
    margin: 0; 
    padding: 0;
    background-color: #0b170e; /* Verde bosque muy oscuro de fondo */
    color: #efe6d3; /* Blanco crudo premium */
    font-family: 'Poppins', sans-serif;
    -webkit-print-color-adjust: exact;
}

/* MARCOS */
.wrapper {
    position: absolute;
    top: 25px; bottom: 25px; left: 25px; right: 25px;
    border: 1.5px solid #c5a059;
    padding: 10px;
    background-color: transparent;
}
.inner-wrapper {
    position: absolute;
    top: 12px; bottom: 12px; left: 12px; right: 12px;
    border: 1px dashed rgba(197, 160, 89, 0.3);
    text-align: center;
}

/* ESQUINAS SIMPLES EN LUGAR DE SVG */
.corner { position: absolute; font-size: 24px; color: #c5a059; line-height: 1; }
.c-tl { top: 6px; left: 10px; }
.c-tr { top: 6px; right: 10px; }
.c-bl { bottom: 6px; left: 10px; }
.c-br { bottom: 6px; right: 10px; }

/* WATERMARK CENTRAL */
.watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-family: 'Lora', serif; font-weight: 700;
    font-size: 150px; color: rgba(197, 160, 89, 0.04);
    letter-spacing: 15px; z-index: -1;
}

/* FECHA E ID */
.id-date {
    position: absolute; 
    top: 30px; right: 40px;
    text-align: right; font-size: 10px;
    color: rgba(197, 160, 89, 0.5);
    letter-spacing: 2px;
    line-height: 1.6;
    text-transform: uppercase;
}
.id-date b { color: #c5a059; font-weight: normal; }

/* CONTENIDO PRINCIPAL */
.content {
    margin-top: 50px;
}
.prog {
    font-size: 11px; letter-spacing: 6px;
    color: rgba(197, 160, 89, 0.8);
    text-transform: uppercase;
    margin-bottom: 25px;
}
.emblema-simple {
    color: #c5a059;
    font-size: 24px;
    margin-bottom: 10px;
}
.tit {
    font-family: 'Lora', serif; font-weight: 700;
    font-size: 42px; color: #efe6d3;
    letter-spacing: 6px; text-transform: uppercase;
    margin-bottom: 5px;
}
.sub {
    font-family: 'Lora', serif; font-style: italic;
    font-size: 18px; color: #c5a059;
    letter-spacing: 2px;
    margin-bottom: 30px;
}
.drule {
    width: 350px; height: 1px;
    background-color: #c5a059;
    margin: 0 auto 30px auto;
}
.pres {
    font-size: 11px; letter-spacing: 4px;
    color: rgba(239, 230, 211, 0.6);
    text-transform: uppercase;
    margin-bottom: 20px;
}
.name {
    font-family: 'Lora', serif; font-style: italic; font-weight: 600;
    font-size: 58px; color: #efe6d3;
    margin-bottom: 10px;
}
.desc {
    font-family: 'Lora', serif; font-size: 14px;
    line-height: 1.8; color: rgba(239, 230, 211, 0.7);
    width: 75%; margin: 0 auto 35px auto;
}
.pill {
    display: inline-block;
    border: 1px solid rgba(197, 160, 89, 0.4);
    background-color: rgba(197, 160, 89, 0.05);
    padding: 8px 30px;
    font-size: 12px; letter-spacing: 3px;
    color: rgba(197, 160, 89, 0.8); text-transform: uppercase;
}
.pill b { color: #d4a84d; font-weight: bold; }

/* FOOTER USANDO TABLA PARA COMPATIBILIDAD CON DOMPDF */
.foot-container {
    position: absolute; bottom: 40px; width: 100%;
}
.footer-table {
    width: 85%; margin: 0 auto; border-collapse: collapse;
}
.footer-table td {
    width: 33.33%; text-align: center; vertical-align: bottom;
}
.sbar {
    width: 180px; height: 1px; 
    background-color: rgba(239, 230, 211, 0.3); 
    margin: 0 auto 8px auto; 
}
.slbl { 
    font-size: 10px; letter-spacing: 2px; 
    color: rgba(197, 160, 89, 0.6); text-transform: uppercase; 
}

/* SELLO PREMIUM (CSS PURO) */
.seal-outer {
    width: 90px; height: 90px;
    border-radius: 45px;
    background-color: #c5a059;
    margin: 0 auto;
    position: relative;
}
.seal-inner {
    width: 78px; height: 78px;
    border-radius: 39px;
    border: 1px dashed #0b170e;
    position: absolute; top: 5px; left: 5px;
}
.seal-text {
    position: absolute; top: 32px; width: 100%;
    text-align: center; font-family: 'Lora', serif;
    font-size: 13px; font-weight: bold; color: #0b170e;
    letter-spacing: 1px;
}
.seal-year {
    position: absolute; top: 48px; width: 100%;
    text-align: center; font-family: 'Poppins', sans-serif;
    font-size: 10px; color: rgba(11, 23, 14, 0.8);
}
</style>
</head>
<body>

<div class="watermark">ECOTOP</div>

<div class="wrapper">
    <div class="inner-wrapper">
        <div class="corner c-tl">&#x25A3;</div>
        <div class="corner c-tr">&#x25A3;</div>
        <div class="corner c-bl">&#x25A3;</div>
        <div class="corner c-br">&#x25A3;</div>

        <div class="id-date">
            <b>ID:</b> {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}<br>
            <b>Emisión:</b> {{ now()->format('d M, Y') }}
        </div>

        <div class="content">
            <div class="prog">Programa Expedición Ecotop</div>
            <div class="emblema-simple">&#10022;</div>

            <div class="tit">Certificado de Excelencia</div>
            <div class="sub">Condecoración al Mérito Ambiental</div>

            <div class="drule"></div>

            <div class="pres">Se otorga el presente diploma a</div>

            <div class="name">{{ $user->name }}</div>

            <div class="drule" style="width: 250px; opacity: 0.5; margin-top: 15px;"></div>

            <div class="desc">
                Por haber completado con dedicación y destreza los desafíos de los biomas colombianos,
                demostrando un excepcional compromiso y liderazgo en la conservación de nuestros ecosistemas.
            </div>

            <div class="pill">
                Rango: <b>{{ $title ?? 'Guardián del Ecosistema' }}</b> &nbsp;&nbsp;&bull;&nbsp;&nbsp; Puntaje: <b>{{ $totalScore }}</b>
            </div>
        </div>

        <div class="foot-container">
            <table class="footer-table">
                <tr>
                    <td>
                        <div class="sbar"></div>
                        <div class="slbl">Dirección General</div>
                    </td>
                    <td>
                        <div class="seal-outer">
                            <div class="seal-inner"></div>
                            <div class="seal-text">ECOTOP</div>
                            <div class="seal-year">{{ now()->format('Y') }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="sbar"></div>
                        <div class="slbl">Comité Evaluador</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>