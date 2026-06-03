<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;700&display=swap');

/* Fallback para Pagella (usaremos Georgia como equivalente clásico) */
@font-face { font-family:'Pagella'; src: local('Georgia'); font-weight:400; font-style:normal; }
@font-face { font-family:'Pagella'; src: local('Georgia Italic'); font-weight:400; font-style:italic; }
@font-face { font-family:'Pagella'; src: local('Georgia Bold'); font-weight:700; font-style:normal; }

@page { size:297mm 210mm; margin:0; }
*{ margin:0; padding:0; box-sizing:border-box; }

body{
  width:297mm; height:210mm;
  font-family:'Pagella',serif;
  -webkit-print-color-adjust:exact;
  print-color-adjust:exact;
  overflow:hidden;
  position:relative;
}

/* ── FONDO ── */
.bg{
  position:absolute; inset:0;
  background:
    radial-gradient(ellipse 110% 90% at 50% 0%,  #1c3524 0%, transparent 50%),
    radial-gradient(ellipse 70%  70% at 15% 50%, #10200f 0%, transparent 55%),
    radial-gradient(ellipse 70%  70% at 85% 50%, #10200f 0%, transparent 55%),
    radial-gradient(ellipse 90% 80% at 50% 100%, #081208 0%, transparent 60%),
    linear-gradient(175deg, #0f2016 0%, #081208 100%);
}

/* Textura sutil */
.tex{
  position:absolute; inset:0; opacity:0.025;
  background-image:
    repeating-linear-gradient(0deg, transparent, transparent 4px, rgba(197,160,89,0.6) 4px, rgba(197,160,89,0.6) 4.4px),
    repeating-linear-gradient(90deg, transparent, transparent 4px, rgba(197,160,89,0.25) 4px, rgba(197,160,89,0.25) 4.4px);
}

/* Aureola central */
.glow{
  position:absolute; top:50%; left:50%;
  transform:translate(-50%,-50%);
  width:240mm; height:160mm;
  background:radial-gradient(ellipse 75% 65% at 50% 50%,
    rgba(197,160,89,0.095) 0%,
    rgba(197,160,89,0.04) 35%,
    transparent 60%
  );
}

/* Aureola superior */
.glow-t{
  position:absolute; top:-5mm; left:50%;
  transform:translateX(-50%);
  width:180mm; height:90mm;
  background:radial-gradient(ellipse 80% 70% at 50% 15%,
    rgba(197,160,89,0.07) 0%, transparent 65%);
}

/* ── MARCOS ── */
.b1{ position:absolute; top:5.5mm; left:5.5mm; right:5.5mm; bottom:5.5mm; border:1.8px solid #c5a059; }
.b2{ position:absolute; top:9.5mm; left:9.5mm; right:9.5mm; bottom:9.5mm; border:0.5px solid rgba(197,160,89,0.38); }
.b3{ position:absolute; top:12mm; left:12mm; right:12mm; bottom:12mm; border:0.35px dashed rgba(197,160,89,0.15); }

/* ── ESQUINAS ── */
.c{ position:absolute; }
.c-tl{ top:1.5mm; left:1.5mm; }
.c-tr{ top:1.5mm; right:1.5mm; transform:scaleX(-1); }
.c-bl{ bottom:1.5mm; left:1.5mm; transform:scaleY(-1); }
.c-br{ bottom:1.5mm; right:1.5mm; transform:scale(-1,-1); }

/* ── LÍNEAS LATERALES ── */
.vl,.vr{
  position:absolute; width:0.3px; top:5.5mm; bottom:5.5mm;
  background:linear-gradient(to bottom, transparent, rgba(197,160,89,0.5) 18%, rgba(197,160,89,0.65) 50%, rgba(197,160,89,0.5) 82%, transparent);
}
.vl{ left:21mm; }
.vr{ right:21mm; }

/* ── ORNAMENTO BOTÁNICO LATERAL ── */
.bot-l,.bot-r{
  position:absolute; top:50%; opacity:0.14;
}
.bot-l{ left:14mm; transform:translateY(-50%); }
.bot-r{ right:14mm; transform:translateY(-50%); }

/* ── WATERMARK ── */
.wm{
  position:absolute; top:44%; left:50%;
  transform:translate(-50%,-50%);
  font-family:'Lora',serif; font-weight:700;
  font-size:98pt; color:rgba(197,160,89,0.032);
  letter-spacing:12px; white-space:nowrap;
}

/* ── ID ── */
.id{
  position:absolute; top:7.5mm; right:27mm;
  font-family:'Poppins',sans-serif; font-weight:300;
  font-size:5pt; letter-spacing:1.5px;
  text-transform:uppercase; color:rgba(197,160,89,0.35);
  text-align:right; line-height:2;
}
.id b{ color:rgba(197,160,89,0.7); font-weight:400; }

/* ── CONTENIDO ── */
.cnt{
  position:absolute;
  top:14mm; left:25mm; right:25mm; bottom:22mm;
  display:flex; flex-direction:column;
  align-items:center; justify-content:center;
}

.prog{
  font-family:'Poppins',sans-serif; font-weight:300;
  font-size:6.2pt; letter-spacing:5.5px;
  color:rgba(197,160,89,0.58); text-transform:uppercase;
  margin-bottom:2mm;
}

.sep{
  display:flex; align-items:center; gap:2.5mm;
  margin-bottom:2mm;
}
.sep .L{ width:20mm; height:0.5px; background:linear-gradient(to right, transparent, rgba(197,160,89,0.5)); }
.sep .R{ width:20mm; height:0.5px; background:linear-gradient(to left, transparent, rgba(197,160,89,0.5)); }
.sep .gem{ color:#c5a059; font-size:7.5pt; }

/* Emblema */
.emb{ margin-bottom:1.8mm; }

.tit{
  font-family:'Lora',serif; font-weight:700;
  font-size:27pt; color:#efe6d3;
  letter-spacing:6.5px; text-transform:uppercase;
  text-align:center; line-height:1;
  margin-bottom:1mm;
}

.sub{
  font-family:'Lora',serif; font-style:italic; font-weight:400;
  font-size:10pt; color:#c5a059; letter-spacing:1.5px;
  margin-bottom:3.5mm;
}

.drule{ width:70mm; margin:0 auto 3mm auto; }
.drule .r1{
  height:1.5px;
  background:linear-gradient(to right, transparent, rgba(197,160,89,0.38) 8%, #c5a059 50%, rgba(197,160,89,0.38) 92%, transparent);
  margin-bottom:1.8px;
}
.drule .r2{
  height:0.5px;
  background:linear-gradient(to right, transparent, rgba(197,160,89,0.22) 12%, rgba(197,160,89,0.5) 50%, rgba(197,160,89,0.22) 88%, transparent);
}

.pres{
  font-family:'Poppins',sans-serif; font-weight:300;
  font-size:5.8pt; letter-spacing:3.5px;
  color:rgba(239,230,211,0.36); text-transform:uppercase;
  margin-bottom:1.5mm;
}

.name{
  font-family:'Lora',serif; font-style:italic; font-weight:600;
  font-size:38pt; color:#efe6d3;
  line-height:1; margin-bottom:0.8mm; letter-spacing:0.5px;
}

.nrule{
  display:flex; align-items:center; justify-content:center;
  gap:2mm; margin-bottom:3mm;
}
.nrule .LR{ width:26mm; height:0.8px; background:linear-gradient(to right, transparent, rgba(197,160,89,0.65)); }
.nrule .RL{ width:26mm; height:0.8px; background:linear-gradient(to left, transparent, rgba(197,160,89,0.65)); }
.nrule .dia{
  width:4mm; height:4mm; background:#c5a059;
  transform:rotate(45deg); flex-shrink:0;
}

.desc{
  font-family:'Pagella',serif; font-size:8pt; line-height:1.85;
  color:rgba(239,230,211,0.52); max-width:136mm;
  text-align:center; margin-bottom:3mm;
}

.pill{
  border:0.5px solid rgba(197,160,89,0.42);
  background:rgba(197,160,89,0.055);
  padding:1.8mm 7mm;
  font-family:'Poppins',sans-serif; font-weight:300;
  font-size:5.5pt; letter-spacing:3px; color:rgba(197,160,89,0.72);
  text-transform:uppercase;
}
.pill b{ font-weight:700; color:#d4a84d; }

/* ── FOOTER ── */
.foot{
  position:absolute;
  bottom:7mm; left:25mm; right:25mm;
}

.foot-rule{
  display:block; width:100%;
  height:4mm; margin-bottom:3.5mm;
}

.foot-row{
  display:flex; justify-content:space-between; align-items:flex-end;
}

.sc{ text-align:center; width:48mm; }
.sbar{ width:36mm; height:0.5px; background:rgba(239,230,211,0.2); margin:0 auto 1.5mm auto; }
.slbl{
  font-family:'Poppins',sans-serif; font-weight:300;
  font-size:5pt; letter-spacing:2.5px;
  color:rgba(197,160,89,0.42); text-transform:uppercase;
}

.seal-c{ text-align:center; }
</style>
</head>
<body>

<div class="bg"></div>
<div class="tex"></div>
<div class="glow"></div>
<div class="glow-t"></div>

<div class="wm">ECOTOP</div>

<div class="b1"></div>
<div class="b2"></div>
<div class="b3"></div>

<!-- ESQUINAS -->
<div class="c c-tl"><svg width="28mm" height="28mm" viewBox="0 0 106 106" xmlns="http://www.w3.org/2000/svg">
  <path d="M3 3 L3 48 M3 3 L48 3" stroke="#c5a059" stroke-width="2.4" fill="none" stroke-linecap="square"/>
  <path d="M3 3 L3 38 M3 3 L38 3" stroke="#c5a059" stroke-width="0.6" fill="none" opacity="0.4"/>
  <path d="M3 3 L3 27 M3 3 L27 3" stroke="rgba(197,160,89,0.2)" stroke-width="0.4" fill="none"/>
  <circle cx="3" cy="3" r="5" fill="#c5a059"/>
  <circle cx="3" cy="3" r="2.8" fill="#0f2016"/>
  <circle cx="3" cy="3" r="1.3" fill="#c5a059"/>
  <path d="M3 24 Q20 20 24 3" stroke="#c5a059" stroke-width="0.9" fill="none" opacity="0.6"/>
  <path d="M3 34 Q30 30 34 3" stroke="rgba(197,160,89,0.22)" stroke-width="0.5" fill="none"/>
  <rect x="23" y="-1" width="7" height="7" fill="#c5a059" transform="rotate(45 26.5 2.5)" opacity="0.8"/>
  <rect x="-1" y="23" width="7" height="7" fill="#c5a059" transform="rotate(45 2.5 26.5)" opacity="0.8"/>
</svg></div>
<div class="c c-tr"><svg width="28mm" height="28mm" viewBox="0 0 106 106" xmlns="http://www.w3.org/2000/svg">
  <path d="M3 3 L3 48 M3 3 L48 3" stroke="#c5a059" stroke-width="2.4" fill="none" stroke-linecap="square"/>
  <path d="M3 3 L3 38 M3 3 L38 3" stroke="#c5a059" stroke-width="0.6" fill="none" opacity="0.4"/>
  <path d="M3 3 L3 27 M3 3 L27 3" stroke="rgba(197,160,89,0.2)" stroke-width="0.4" fill="none"/>
  <circle cx="3" cy="3" r="5" fill="#c5a059"/>
  <circle cx="3" cy="3" r="2.8" fill="#0f2016"/>
  <circle cx="3" cy="3" r="1.3" fill="#c5a059"/>
  <path d="M3 24 Q20 20 24 3" stroke="#c5a059" stroke-width="0.9" fill="none" opacity="0.6"/>
  <path d="M3 34 Q30 30 34 3" stroke="rgba(197,160,89,0.22)" stroke-width="0.5" fill="none"/>
  <rect x="23" y="-1" width="7" height="7" fill="#c5a059" transform="rotate(45 26.5 2.5)" opacity="0.8"/>
  <rect x="-1" y="23" width="7" height="7" fill="#c5a059" transform="rotate(45 2.5 26.5)" opacity="0.8"/>
</svg></div>
<div class="c c-bl"><svg width="28mm" height="28mm" viewBox="0 0 106 106" xmlns="http://www.w3.org/2000/svg">
  <path d="M3 3 L3 48 M3 3 L48 3" stroke="#c5a059" stroke-width="2.4" fill="none" stroke-linecap="square"/>
  <path d="M3 3 L3 38 M3 3 L38 3" stroke="#c5a059" stroke-width="0.6" fill="none" opacity="0.4"/>
  <path d="M3 3 L3 27 M3 3 L27 3" stroke="rgba(197,160,89,0.2)" stroke-width="0.4" fill="none"/>
  <circle cx="3" cy="3" r="5" fill="#c5a059"/>
  <circle cx="3" cy="3" r="2.8" fill="#0f2016"/>
  <circle cx="3" cy="3" r="1.3" fill="#c5a059"/>
  <path d="M3 24 Q20 20 24 3" stroke="#c5a059" stroke-width="0.9" fill="none" opacity="0.6"/>
  <path d="M3 34 Q30 30 34 3" stroke="rgba(197,160,89,0.22)" stroke-width="0.5" fill="none"/>
  <rect x="23" y="-1" width="7" height="7" fill="#c5a059" transform="rotate(45 26.5 2.5)" opacity="0.8"/>
  <rect x="-1" y="23" width="7" height="7" fill="#c5a059" transform="rotate(45 2.5 26.5)" opacity="0.8"/>
</svg></div>
<div class="c c-br"><svg width="28mm" height="28mm" viewBox="0 0 106 106" xmlns="http://www.w3.org/2000/svg">
  <path d="M3 3 L3 48 M3 3 L48 3" stroke="#c5a059" stroke-width="2.4" fill="none" stroke-linecap="square"/>
  <path d="M3 3 L3 38 M3 3 L38 3" stroke="#c5a059" stroke-width="0.6" fill="none" opacity="0.4"/>
  <path d="M3 3 L3 27 M3 3 L27 3" stroke="rgba(197,160,89,0.2)" stroke-width="0.4" fill="none"/>
  <circle cx="3" cy="3" r="5" fill="#c5a059"/>
  <circle cx="3" cy="3" r="2.8" fill="#0f2016"/>
  <circle cx="3" cy="3" r="1.3" fill="#c5a059"/>
  <path d="M3 24 Q20 20 24 3" stroke="#c5a059" stroke-width="0.9" fill="none" opacity="0.6"/>
  <path d="M3 34 Q30 30 34 3" stroke="rgba(197,160,89,0.22)" stroke-width="0.5" fill="none"/>
  <rect x="23" y="-1" width="7" height="7" fill="#c5a059" transform="rotate(45 26.5 2.5)" opacity="0.8"/>
  <rect x="-1" y="23" width="7" height="7" fill="#c5a059" transform="rotate(45 2.5 26.5)" opacity="0.8"/>
</svg></div>

<div class="vl"></div>
<div class="vr"></div>

<!-- BOTÁNICO IZQUIERDO -->
<div class="bot-l">
  <svg width="11mm" height="60mm" viewBox="0 0 42 227" xmlns="http://www.w3.org/2000/svg">
    <line x1="21" y1="4" x2="21" y2="223" stroke="#c5a059" stroke-width="1.4"/>
    <path d="M21 38 Q3 30 8 14 Q21 30 21 38Z" fill="#c5a059"/>
    <path d="M21 38 Q39 30 34 14 Q21 30 21 38Z" fill="#c5a059"/>
    <path d="M21 75 Q3 67 8 51 Q21 67 21 75Z" fill="#c5a059"/>
    <path d="M21 75 Q39 67 34 51 Q21 67 21 75Z" fill="#c5a059"/>
    <path d="M21 113 Q3 105 8 89 Q21 105 21 113Z" fill="#c5a059"/>
    <path d="M21 113 Q39 105 34 89 Q21 105 21 113Z" fill="#c5a059"/>
    <path d="M21 151 Q3 143 8 127 Q21 143 21 151Z" fill="#c5a059"/>
    <path d="M21 151 Q39 143 34 127 Q21 143 21 151Z" fill="#c5a059"/>
    <path d="M21 189 Q3 181 8 165 Q21 181 21 189Z" fill="#c5a059"/>
    <path d="M21 189 Q39 181 34 165 Q21 181 21 189Z" fill="#c5a059"/>
    <circle cx="21" cy="4"   r="3" fill="#c5a059"/>
    <circle cx="21" cy="223" r="3" fill="#c5a059"/>
    <circle cx="21" cy="113" r="5.5" fill="#c5a059"/>
    <circle cx="21" cy="113" r="2.2" fill="#0f2016"/>
  </svg>
</div>
<div class="bot-r">
  <svg width="11mm" height="60mm" viewBox="0 0 42 227" xmlns="http://www.w3.org/2000/svg">
    <line x1="21" y1="4" x2="21" y2="223" stroke="#c5a059" stroke-width="1.4"/>
    <path d="M21 38 Q3 30 8 14 Q21 30 21 38Z" fill="#c5a059"/>
    <path d="M21 38 Q39 30 34 14 Q21 30 21 38Z" fill="#c5a059"/>
    <path d="M21 75 Q3 67 8 51 Q21 67 21 75Z" fill="#c5a059"/>
    <path d="M21 75 Q39 67 34 51 Q21 67 21 75Z" fill="#c5a059"/>
    <path d="M21 113 Q3 105 8 89 Q21 105 21 113Z" fill="#c5a059"/>
    <path d="M21 113 Q39 105 34 89 Q21 105 21 113Z" fill="#c5a059"/>
    <path d="M21 151 Q3 143 8 127 Q21 143 21 151Z" fill="#c5a059"/>
    <path d="M21 151 Q39 143 34 127 Q21 143 21 151Z" fill="#c5a059"/>
    <path d="M21 189 Q3 181 8 165 Q21 181 21 189Z" fill="#c5a059"/>
    <path d="M21 189 Q39 181 34 165 Q21 181 21 189Z" fill="#c5a059"/>
    <circle cx="21" cy="4"   r="3" fill="#c5a059"/>
    <circle cx="21" cy="223" r="3" fill="#c5a059"/>
    <circle cx="21" cy="113" r="5.5" fill="#c5a059"/>
    <circle cx="21" cy="113" r="2.2" fill="#0f2016"/>
  </svg>
</div>

<!-- ARCO SUPERIOR -->
<svg style="position:absolute;top:5.5mm;left:50%;transform:translateX(-50%);"
     width="95mm" height="15mm" viewBox="0 0 359 57" xmlns="http://www.w3.org/2000/svg">
  <path d="M4 52 Q179 2 354 52" stroke="#c5a059" stroke-width="1.1" fill="none" opacity="0.7"/>
  <path d="M16 52 Q179 9 342 52" stroke="#c5a059" stroke-width="0.4" fill="none" opacity="0.28"/>
  <circle cx="4"   cy="52" r="2.5" fill="#c5a059" opacity="0.8"/>
  <circle cx="354" cy="52" r="2.5" fill="#c5a059" opacity="0.8"/>
  <circle cx="179" cy="4"  r="3.5" fill="#c5a059"/>
  <rect x="174" y="-0.5" width="10" height="10" fill="none" stroke="#c5a059" stroke-width="0.7" transform="rotate(45 179 4.5)" opacity="0.55"/>
  <circle cx="91"  cy="25" r="1.8" fill="#c5a059" opacity="0.5"/>
  <circle cx="267" cy="25" r="1.8" fill="#c5a059" opacity="0.5"/>
</svg>

<!-- ARCO INFERIOR -->
<svg style="position:absolute;bottom:5.5mm;left:50%;transform:translateX(-50%) scaleY(-1);"
     width="95mm" height="15mm" viewBox="0 0 359 57" xmlns="http://www.w3.org/2000/svg">
  <path d="M4 52 Q179 2 354 52" stroke="#c5a059" stroke-width="1.1" fill="none" opacity="0.7"/>
  <path d="M16 52 Q179 9 342 52" stroke="#c5a059" stroke-width="0.4" fill="none" opacity="0.28"/>
  <circle cx="4"   cy="52" r="2.5" fill="#c5a059" opacity="0.8"/>
  <circle cx="354" cy="52" r="2.5" fill="#c5a059" opacity="0.8"/>
  <circle cx="179" cy="4"  r="3.5" fill="#c5a059"/>
  <circle cx="91"  cy="25" r="1.8" fill="#c5a059" opacity="0.5"/>
  <circle cx="267" cy="25" r="1.8" fill="#c5a059" opacity="0.5"/>
</svg>

<!-- ID -->
<div class="id">
  <b>ID:</b> {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}<br>
  <b>Emisión:</b> {{ now()->format('d M, Y') }}
</div>

<!-- ═══════════ CONTENIDO CENTRAL ═══════════ -->
<div class="cnt">

  <div class="prog">Programa Expedición Ecotop</div>

  <div class="sep">
    <div class="L"></div>
    <span class="gem">✦</span>
    <div class="R"></div>
  </div>

  <!-- EMBLEMA -->
  <div class="emb">
    <svg width="23mm" height="23mm" viewBox="0 0 87 87" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <radialGradient id="eha" cx="50%" cy="50%" r="50%">
          <stop offset="0%" stop-color="#c5a059" stop-opacity="0.2"/>
          <stop offset="100%" stop-color="#c5a059" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="lf" cx="50%" cy="35%" r="60%">
          <stop offset="0%" stop-color="#e0c070"/>
          <stop offset="55%" stop-color="#c5a059"/>
          <stop offset="100%" stop-color="#9a7838"/>
        </radialGradient>
      </defs>
      <circle cx="43" cy="43" r="42" fill="url(#eha)"/>
      <circle cx="43" cy="43" r="41.5" stroke="#c5a059" stroke-width="0.9" fill="none"/>
      <circle cx="43" cy="43" r="36.5" stroke="#c5a059" stroke-width="0.45" fill="none" stroke-dasharray="1.5 2.2"/>
      <!-- 4 hojas principales -->
      <path d="M43 9 Q50 24 43 36 Q36 24 43 9Z"    fill="url(#lf)"/>
      <path d="M43 77 Q50 62 43 50 Q36 62 43 77Z"   fill="url(#lf)"/>
      <path d="M9 43 Q24 36 36 43 Q24 50 9 43Z"     fill="url(#lf)"/>
      <path d="M77 43 Q62 36 50 43 Q62 50 77 43Z"   fill="url(#lf)"/>
      <!-- 4 hojas diagonales pequeñas -->
      <path d="M18 18 Q26 26 22 33 Q16 25 18 18Z"   fill="#c5a059" opacity="0.55"/>
      <path d="M68 18 Q60 26 64 33 Q70 25 68 18Z"   fill="#c5a059" opacity="0.55"/>
      <path d="M18 68 Q26 60 22 53 Q16 61 18 68Z"   fill="#c5a059" opacity="0.55"/>
      <path d="M68 68 Q60 60 64 53 Q70 61 68 68Z"   fill="#c5a059" opacity="0.55"/>
      <!-- Cruz sutil -->
      <line x1="43" y1="11" x2="43" y2="75" stroke="#c5a059" stroke-width="0.45" opacity="0.25"/>
      <line x1="11" y1="43" x2="75" y2="43" stroke="#c5a059" stroke-width="0.45" opacity="0.25"/>
      <!-- Centro -->
      <circle cx="43" cy="43" r="9.5" fill="none" stroke="url(#lf)" stroke-width="1.2"/>
      <circle cx="43" cy="43" r="6"   fill="url(#lf)"/>
      <circle cx="43" cy="43" r="2.5" fill="#0f2016"/>
      <!-- Punto central brillante -->
      <circle cx="43" cy="43" r="1" fill="#f0d890" opacity="0.8"/>
    </svg>
  </div>

  <!-- TÍTULO -->
  <div class="tit">Certificado&nbsp;&nbsp;de&nbsp;&nbsp;Excelencia</div>
  <div class="sub">Condecoración al Mérito Ambiental</div>

  <div class="drule"><div class="r1"></div><div class="r2"></div></div>

  <div class="pres">Se otorga el presente diploma a</div>

  <div class="name">{{ $user->name }}</div>

  <div class="nrule">
    <div class="LR"></div>
    <div class="dia"></div>
    <div class="RL"></div>
  </div>

  <div class="desc">
    Por haber completado con dedicación y destreza los desafíos de los biomas colombianos,
    demostrando un excepcional compromiso y liderazgo en la conservación de nuestros ecosistemas.
  </div>

  <div class="pill">
    Rango:&nbsp;<b>{{ $title ?? 'Guardián del Ecosistema' }}</b>&nbsp;&nbsp;&#x25CF;&nbsp;&nbsp;Puntaje:&nbsp;<b>{{ $totalScore }}</b>
  </div>

</div>

<!-- ═══════════ FOOTER ═══════════ -->
<div class="foot">

  <svg class="foot-rule" viewBox="0 0 950 18" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <line x1="0"   y1="9" x2="370" y2="9" stroke="rgba(197,160,89,0.22)" stroke-width="0.6"/>
    <line x1="580" y1="9" x2="950" y2="9" stroke="rgba(197,160,89,0.22)" stroke-width="0.6"/>
    <circle cx="370" cy="9" r="2" fill="#c5a059" opacity="0.5"/>
    <circle cx="580" cy="9" r="2" fill="#c5a059" opacity="0.5"/>
    <!-- Motivo central -->
    <path d="M420 9 L425 4 L430 9 L435 4 L440 9 L445 4 L450 9 L455 4 L460 9 L465 4 L470 9 L475 4 L480 9 L485 4 L490 9 L495 4 L500 9 L505 4 L510 9 L515 4 L520 9 L525 4 L530 9"
          stroke="#c5a059" stroke-width="0.75" fill="none" opacity="0.55"/>
    <polygon points="473,2 478,14 483,2" fill="#c5a059" opacity="0.85"/>
  </svg>

  <div class="foot-row">
    <div class="sc">
      <div class="sbar"></div>
      <div class="slbl">Dirección General</div>
    </div>

    <!-- SELLO PREMIUM -->
    <div class="seal-c">
      <svg width="25mm" height="25mm" viewBox="0 0 95 95" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <radialGradient id="sg2" cx="50%" cy="38%" r="58%">
            <stop offset="0%" stop-color="#e8c870"/>
            <stop offset="50%" stop-color="#c5a059"/>
            <stop offset="100%" stop-color="#7e5e20"/>
          </radialGradient>
          <radialGradient id="bg2" cx="50%" cy="40%" r="55%">
            <stop offset="0%" stop-color="#162b1d"/>
            <stop offset="100%" stop-color="#0c1c12"/>
          </radialGradient>
        </defs>
        <!-- Estrella de fondo de 16 puntas -->
        <path d="
          M47 4 L49.8 18 L61 8.5 L54.5 21 L68 17.5 L58 28.5 L72 30 L59.5 36.5 L71.5 44
          L57.5 42 L61 57 L52 46 L54 62 L47 50.5 L40 62 L42 46 L33 57 L36.5 42
          L22.5 44 L34.5 36.5 L22 30 L36 28.5 L26 17.5 L39.5 21 L33 8.5 L44.2 18 Z"
          fill="url(#sg2)" opacity="0.9"/>
        <!-- 8 pequeños diamantes en la estrella -->
        <rect x="43.5" y="0" width="7" height="7" fill="#c5a059" transform="rotate(45 47 3.5)" opacity="0.65"/>
        <!-- Círculo principal del sello -->
        <circle cx="47" cy="46" r="26.5" fill="url(#bg2)" stroke="#c5a059" stroke-width="1.5"/>
        <circle cx="47" cy="46" r="23.5" fill="none" stroke="rgba(197,160,89,0.28)" stroke-width="0.5" stroke-dasharray="2 2.5"/>
        <circle cx="47" cy="46" r="20.5" fill="none" stroke="rgba(197,160,89,0.12)" stroke-width="0.4"/>
        <!-- Hoja en sello -->
        <path d="M47 24 Q50.5 31.5 47 39 Q43.5 31.5 47 24Z" fill="#c5a059" opacity="0.85"/>
        <!-- Texto ECOTOP -->
        <text x="47" y="44" text-anchor="middle"
              font-family="Lora, serif" font-weight="700"
              font-size="9" fill="#c5a059" letter-spacing="0.8">ECOTOP</text>
        <text x="47" y="54.5" text-anchor="middle"
              font-family="Poppins, sans-serif" font-weight="300"
              font-size="6.5" fill="rgba(197,160,89,0.6)" letter-spacing="0.3">2026</text>
        <!-- Pequeño diamante inferior en sello -->
        <rect x="43.5" y="60" width="7" height="7" fill="#c5a059" transform="rotate(45 47 63.5)" opacity="0.6"/>
      </svg>
    </div>

    <div class="sc">
      <div class="sbar"></div>
      <div class="slbl">Comité Evaluador</div>
    </div>
  </div>
</div>

</body>
</html>