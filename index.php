<?php
include("conexion.php");


// Mostrar mensaje si viene por GET
$mensaje = $_GET['mensaje'] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Evitamos warnings usando ?? para valores vacíos
    $mes = $_POST['mes'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $area = $_POST['area'] ?? '';
    $centro_costo = $_POST['centro_costo'] ?? '';
    $quien_solicita = $_POST['responsable'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $tema = $_POST['tema'] ?? '';
    $solicitud = $_POST['solicitud'] ?? '';
    $estado = "Pendiente";

    $query = "INSERT INTO tickets (mes, fecha, area, centro_costo, quien_solicita, correo, tema, solicitud, estado)
              VALUES ('$mes','$fecha','$area','$centro_costo','$quien_solicita','$correo','$tema','$solicitud','$estado')";

    if ($conn->query($query)) {
        $mensaje = " Tu ticket fue montado exitosamente y será solucionado lo mas pronto posible.";
    } else {
        $mensaje = " Error al enviar el ticket: " . $conn->error;
    }
    // Redirigir usando POST/REDIRECT/GET
    header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=" . urlencode($mensaje));
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Formulario de Tickets Aldimark</title>
<style>
    body {
        position: relative;
    }
    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
            background: url('/tickets/img/logo.png') center center/60% no-repeat;
            opacity: 0.13;
        z-index: 0;
        pointer-events: none;
    }
    .container {
        position: relative;
        z-index: 1;
    }
    h1 img {
        animation: logoPop 1.1s cubic-bezier(.4,2,.6,1);
        transition: transform 0.3s, box-shadow 0.3s, filter 0.3s;
        box-shadow: 0 2px 16px 0 rgba(255,75,43,0.10);
        border-radius: 18px;
    }
    h1 img:hover {
        transform: scale(1.08) rotate(-2deg);
        box-shadow: 0 8px 32px 0 rgba(255,75,43,0.18);
        filter: brightness(1.08) drop-shadow(0 0 8px #ffb3b3);
        cursor: pointer;
    }
    @keyframes logoPop {
        0% { transform: scale(0.7) translateY(-40px); opacity: 0; }
        70% { transform: scale(1.1) translateY(8px); opacity: 1; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }
    body {
        opacity: 0;
        transition: opacity 0.7s cubic-bezier(.4,2,.6,1);
    }
    body.animar {
        opacity: 1;
    }
    .container, input, select, textarea, button, .btn-enviar, .btn-admin, label, h1, .mensaje {
        transition: box-shadow 0.3s, background 0.3s, color 0.3s, border 0.3s, transform 0.3s, opacity 0.3s;
    }
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px #ffb3b3;
        background: #fff6f6;
        outline: none;
        transform: scale(1.03);
    }
    select option {
        transition: background 0.2s, color 0.2s;
    }
    select:active, select:focus {
        background: #fff0f0;
    }
    input[type="text"], textarea {
        transition: box-shadow 0.3s, background 0.3s, color 0.3s, border 0.3s, transform 0.3s;
    }
    .btn-enviar:active {
        transform: scale(0.97);
        background: #ffeaea;
    }
    label {
        opacity: 0;
        transform: translateY(20px);
        animation: labelFadeIn 0.7s forwards;
    }
    label:nth-of-type(1) { animation-delay: 0.1s; }
    label:nth-of-type(2) { animation-delay: 0.2s; }
    label:nth-of-type(3) { animation-delay: 0.3s; }
    label:nth-of-type(4) { animation-delay: 0.4s; }
    label:nth-of-type(5) { animation-delay: 0.5s; }
    label:nth-of-type(6) { animation-delay: 0.6s; }
    label:nth-of-type(7) { animation-delay: 0.7s; }
    label:nth-of-type(8) { animation-delay: 0.8s; }
    label:nth-of-type(9) { animation-delay: 0.9s; }
    label:nth-of-type(10) { animation-delay: 1.0s; }
    @keyframes labelFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }
    .container form > * {
        opacity: 0;
        transform: translateY(30px);
        animation: formFadeIn 0.7s forwards;
    }
    .container form > *:nth-child(1) { animation-delay: 0.2s; }
    .container form > *:nth-child(2) { animation-delay: 0.3s; }
    .container form > *:nth-child(3) { animation-delay: 0.4s; }
    .container form > *:nth-child(4) { animation-delay: 0.5s; }
    .container form > *:nth-child(5) { animation-delay: 0.6s; }
    .container form > *:nth-child(6) { animation-delay: 0.7s; }
    .container form > *:nth-child(7) { animation-delay: 0.8s; }
    .container form > *:nth-child(8) { animation-delay: 0.9s; }
    .container form > *:nth-child(9) { animation-delay: 1.0s; }
    .container form > *:nth-child(10) { animation-delay: 1.1s; }
    .container form > *:nth-child(11) { animation-delay: 1.2s; }
    .container form > *:nth-child(12) { animation-delay: 1.3s; }
    .container form > *:nth-child(13) { animation-delay: 1.4s; }
    .container form > *:nth-child(14) { animation-delay: 1.5s; }
    @keyframes formFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }
    #modal-mensaje {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s cubic-bezier(.4,2,.6,1);
    }
    #modal-mensaje.mostrar {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-contenido {
        background: linear-gradient(120deg, #fff 70%, #ffeaea 100%);
        border-radius: 18px;
        padding: 32px 28px 28px 28px;
        box-shadow: 0 8px 32px 0 rgba(245, 101, 75, 0.18), 0 1.5px 4px 0 rgba(180,0,0,0.10);
        text-align: center;
        min-width: 220px;
        max-width: 90vw;
        font-size: 1.2rem;
        position: relative;
        animation: modalPop 0.5s cubic-bezier(.4,2,.6,1);
        border: 2px solid #edbabaff;
    }
    @keyframes modalPop {
        0% { transform: scale(0.7) translateY(40px); opacity: 0; }
        70% { transform: scale(1.05) translateY(-8px); opacity: 1; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }
    .modal-contenido span#cerrar-modal {
        color: #c00;
        font-weight: bold;
        transition: color 0.2s, transform 0.2s;
    }
    .modal-contenido span#cerrar-modal:hover {
        color: #fff;
        background: #c00;
        border-radius: 50%;
        padding: 2px 8px;
        transform: scale(1.2);
        cursor: pointer;
    }
    .modal-contenido {
        box-shadow: 0 8px 32px 0 rgba(255,75,43,0.18), 0 1.5px 4px 0 rgba(180,0,0,0.10);
    }
    body {
        font-family: Tahoma, Arial, sans-serif;
        background: linear-gradient(135deg, rgba(255,255,255,0.85) 40%, #fe5b5b 100%);
        border-bottom: 2px solid #c00;
        color: #000;
        min-height: 100vh;
        margin: 0;
    }
    .container, input, select, textarea, button, .btn-enviar, .btn-admin, label, h1, .mensaje {
        font-family: Tahoma, Arial, sans-serif !important;
    }
    .container { max-width: 600px; margin: 30px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-sizing: border-box; }
    h1 { text-align: center; color: #c00; }
    label {
        display: block;
        margin-top: 10px;
        color: rgba(0, 0, 0, 1);
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    input, select, textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        background: #f8f8f8ec;
        font-size: 16px;
        margin-top: 5px;
        box-sizing: border-box;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        min-width: 0;
    }
    @media (max-width: 600px) {
        .container {
            max-width: 98vw;
            margin: 10px auto;
            padding: 8px;
            border-radius: 7px;
        }
        h1 img {
            max-width: 90vw !important;
        }
        label {
            font-size: 1rem;
            margin-top: 7px;
        }
        input, select, textarea {
            font-size: 15px;
            padding: 8px;
        }
        .btn-enviar {
            width: 100%;
            font-size: 16px;
            padding: 10px 0;
            margin-top: 12px;
        }
        .btn-admin {
            width: 100%;
            font-size: 13px;
            margin-top: 8px;
        }
        .modal-contenido {
            min-width: 0;
            padding: 18px 8px;
            font-size: 1rem;
        }
    }
    .btn-enviar {
        width: 60%;
        margin: 18px auto 0 auto;
        display: block;
        padding: 10px 0;
        border-radius: 25px;
        border: 2px solid #c00;
        background: #fff;
        color: #c00;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(255,75,43,0.10);
        transition: background 0.2s, color 0.2s, border 0.2s, transform 0.2s;
    }
    .btn-enviar:hover {
        background: #c00;
        color: #fff;
        border: 2px solid #ff4b2b;
        transform: scale(1.04);
    }
    .btn-admin {
        width: 18%;
        margin: 8px auto 0 auto;
        display: block;
        padding: 4px 0;
        border-radius: 10px;
        border: none;
        background: #eee;
        color: #c00;
        cursor: pointer;
        font-size: 11px;
        font-weight: normal;
        box-shadow: none;
        opacity: 0.7;
        transition: background 0.2s, color 0.2s, opacity 0.2s;
    }
    .btn-admin:hover {
        background: #f8d7da;
        color: #a00;
        opacity: 1;
    }
    .mensaje { margin-top: 15px; font-weight: bold; text-align: center; }
</style>
<script>
    // Animación de entrada de la página
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function(){
            document.body.classList.add('animar');
        }, 80);
    });

    // Animación para selects y campos al cambiar
    document.querySelectorAll('select, input, textarea').forEach(function(el) {
        el.addEventListener('change', function() {
            el.style.transition = 'background 0.3s, color 0.3s, box-shadow 0.3s, transform 0.3s';
            el.style.background = '#fff0f0';
            el.style.boxShadow = '0 0 0 3px #ffb3b3';
            el.style.transform = 'scale(1.03)';
            setTimeout(function(){
                el.style.background = '';
                el.style.boxShadow = '';
                el.style.transform = '';
            }, 500);
        });
    });
    function mostrarModal(mensaje) {
        document.getElementById('texto-modal').innerHTML = mensaje;
        var modal = document.getElementById('modal-mensaje');
        modal.classList.add('mostrar');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function cerrarModal() {
        var modal = document.getElementById('modal-mensaje');
        modal.classList.remove('mostrar');
        setTimeout(function(){
            modal.style.display = 'none';
            document.body.style.overflow = '';
            window.scrollTo(0,0);
        }, 350);
    }
    document.addEventListener('DOMContentLoaded', function() {
        var cerrar = document.getElementById('cerrar-modal');
        if(cerrar) cerrar.onclick = cerrarModal;
        var modal = document.getElementById('modal-mensaje');
        if(modal) modal.onclick = function(e) { if(e.target === this) cerrarModal(); };
    });
    // Centros de costo según área
    const centros = {
        "ADM": [
            {centro:"Director General", responsable:"Diego Alfonso Vallejo", correo:"dvallejo@aldimark.com"},
            {centro:"Director Financiero y Administrativo", responsable:"Freddy Acero Latorre", correo:"dirfinanciero@aldimark.com"},
            {centro:"Jefe de Contabilidad", responsable:"Luisa Fernanda Fonseca", correo:"contabilidad@aldimark.com"},
            {centro:"Analista de Contabilidad 1", responsable:"Luis Alejandro Cuellar", correo:"a.contable@aldimark.com"},
            {centro:"Analista de Contabilidad 2", responsable:"Sandra Milena Melo", correo:"a.contable2@aldimark.com"},
            {centro:"Auxiliar Contable", responsable:"Danna Michelle Zamora", correo:"jtesoreria@aldimark.com"},
            {centro:"Tesorera", responsable:"Claudia Marcela Pava", correo:"jtesoreria2@aldimark.com"},
            {centro:"Analista de Cartera", responsable:"Cristian Orlando Barrera", correo:"carteracolegios@aldimark.com"},
            {centro:"Analista de Facturación y Cartera", responsable:"Jineth Tatiana Guiza", correo:"a.facturacion@aldimark.com"},
            {centro:"Lider Costos", responsable:"Luz Adriana Mesa", correo:"lidercostos@aldimark.com"},
            {centro:"Jefe de Compras", responsable:"Jeymmi Almanza Mora", correo:"compras@aldimark.com"},
            {centro:"Asistente de Compras 1", responsable:"Luis Alberto Bobadilla", correo:"compras1@aldimark.com"},
            {centro:"Asistente de Compras 2", responsable:"Yudi Paola Rodriguez", correo:"compras2@aldimark.com"},
            {centro:"Asistente de Compras 3", responsable:"Stephany Diaz Ibañez", correo:"compras3@aldimark.com"},
            {centro:"Jefe de Sistemas", responsable:"Erick Jack Martinez", correo:"sistemas@aldimark.com"},
            {centro:"Asistente de Sistemas", responsable:"Albeiro Jose Herrera", correo:"sistemas-sop@aldimark.com"},
            {centro:"Jefe de Proyecto karta", responsable:"Richard Alfredo Fernandez", correo:"karta@aldimark.com"},
            {centro:"Director Comercial", responsable:"Idianeth Orlay Castillo", correo:"dircomercial@aldimark.com"},
            {centro:"Directora Talento Humano", responsable:"Adriana Maria Avellaneda", correo:"dirtalentohumano@aldimark.com"},
            {centro:"Coordinadora de Compensaciones", responsable:"Dahila Romero Echeverry", correo:"a.nomina@aldimark.com"},
            {centro:"Auxiliar de Compensaciones", responsable:"Heydy Dayana Guette", correo:"a.nomina2@aldimark.com"},
            {centro:"Analista Selección y Contratacion", responsable:"Erinson Salcedo Soriano", correo:"seleccion@aldimark.com"},
            {centro:"Auxiliar Selección y Contratacion", responsable:"Martha Lineth Tapiero", correo:"seleccion2@aldimark.com"},
            {centro:"Analista de Bienestar y Capacitacion", responsable:"Viviana Michelle Castañeda", correo:"bienestar@aldimark.com"},
            {centro:"Auxiliar de Talento Humano y Recepcion", responsable:"Luisa Fernanda Vasquez", correo:"aux-rrhh@aldimark.com"},
            {centro:"Jefe de HSEQ", responsable:"Orlando José Romero", correo:"dirhseq@aldimark.com"},
            {centro:"Lider de Calidad 3", responsable:"Xiomara Paez", correo:"lidercalidad3@aldimark.com"},
            {centro:"Lider de Calidad 4", responsable:"Anyelo Andrey Murilo", correo:"lidercalidad4@aldimark.com"},
            {centro:"Aprendiz Sena Calidad 1", responsable:"Daniela Alejandra Patiño", correo:"senaclaidad1@aldimark.com"},
            {centro:"Aprendiz Sena Calidad 2", responsable:"Angie Viviana Fernandez", correo:"senaclaidad2@aldimark.com"},
            {centro:"Jefe de Seguridad y Salud en el Trabajo", responsable:"Leonardo Javier Cadena", correo:"seguridadysalud@aldimark.com"},
            {centro:"Aprendiz Sena Seguridad y Salud en el Trabajo", responsable:"Wilar David Sanchez", correo:"senasst@aldimark.com"},
            {centro:"Jefe de Planta", responsable:"Giselle Carrasquilla", correo:"jplantaproduccion@aldimark.com"},
            {centro:"Auxiliar de Produccion", responsable:"Angie Yojanna Rodriguez", correo:"auxad-produccion@aldimark.com"},
            {centro:"Jefe de Nutricion", responsable:"Laura Isabella Dueñas", correo:"nutricion@aldimark.com"},
            {centro:"Nutricionista Administrativa 2", responsable:"Laura Carolina Vargas", correo:"nutricion2@aldimark.com"},
            {centro:"Chef Ejecutivo", responsable:"Carlos Aurelio Risso", correo:"gastronomia@aldimark.com"},
            {centro:"Chef Eventos", responsable:"Damian Augusto Lopez", correo:"eventos@aldimark.com"},
            {centro:"Director de Operaciones", responsable:"Jhon Alexander Hernandez", correo:"dir.operaciones@aldimark.com"},
            {centro:"Jefe de Operaciones 1", responsable:"Angela María Arevalo", correo:"joperaciones@aldimark.com"},
            {centro:"Jefe de Operaciones 2", responsable:"Luis Gabriel Castro", correo:"joperaciones2@aldimark.com"},
            {centro:"Jefe de Operaciones 3", responsable:"Luis Dario Torres", correo:"joperaciones3@aldimark.com"},
            {centro:"Jefe de Operaciones 4", responsable:"Luis Fernando Gutierrez", correo:"joperaciones4@aldimark.com"},
            {centro:"Servicio al Cliente", responsable:"Marcela Nava Mejia", correo:"servicioalcliente@aldimark.com"}
        ],
        "CDS": [
            {centro:"Alfagres", responsable:"Yessica Paola Morales", correo:"alfagres@aldimark.com"},
            {centro:"Ceramicas Torino", responsable:"Yessica Paola Morales", correo:"alfagres@aldimark.com"},
            {centro:"Barranquilla", responsable:"Viviana Patricia Cantillo", correo:"barranquilla@aldimark.com"},
            {centro:"Bimbo Bogotá", responsable:"Carmen Andrea Forero", correo:"bimbobogota@aldimark.com"},
            {centro:"Bimbo Cedi", responsable:"Juan Sebastian Fandiño", correo:"bimbocedi@aldimark.com"},
            {centro:"Bimbo Planta", responsable:"Claudia Liliana Cetina", correo:"bimboplanta@aldimark.com"},
            {centro:"Brinsa", responsable:"Sandra Milena Giraldo", correo:"brinsa@aldimark.com"},
            {centro:"Calucé Bogotá", responsable:"Leidy Posada", correo:"calucebogota@aldimark.com"},
            {centro:"Calucé Bogota Nutricion", responsable:"Juanita Castillo Gonzalez", correo:"nutricioncaluce@aldimark.com"},
            {centro:"Calucé Chia", responsable:"Jhon Alexander Rodirguez", correo:"caluce@aldimark.com"},
            {centro:"Calucé Chia Nutricion", responsable:"Juanita Moreno Rodriguez", correo:"nutricioncaluce2@aldimark.com"},
            {centro:"Ceramicas San Lorenzo", responsable:"Angela Rocio Velasquez", correo:"sanlorenzo@aldimark.com"},
            {centro:"Colegio Ciedi", responsable:"Maria Natalia Duran", correo:"ciedi@aldimark.com"},
            {centro:"Colegio Colombo Britanico", responsable:"Lina María Loaiza", correo:"colombobritanico@aldimark.com"},
            {centro:"Colegio Franciscano Virrey Solis", responsable:"Carolina Ortiz", correo:"virreysolis@aldimark.com"},
            {centro:"Colegio Gran Bretaña", responsable:"Juan Nicolas Cabuya", correo:"granbretana@aldimark.com"},
            {centro:"Colegio Hermano Miguel La Salle", responsable:"Monica Trespalacios", correo:"lasalle@aldimark.com"},
            {centro:"Colegio Hispanoamericano", responsable:"Francy Estefany Flecher", correo:"hispanoamericano@aldimark.com"},
            {centro:"Colegio Internacional Sek", responsable:"Lissette Olarte", correo:"saemk@aldimark.com"},
            {centro:"Colegio La Victoria", responsable:"Michelle Avila", correo:"victoria@aldimark.com"},
            {centro:"Colegio San Bartolomé (Bachillerato)", responsable:"Yussep Felipe Pinzón", correo:"sanbartolome@aldimark.com"},
            {centro:"Colegio San Bartolomé (Primaria)", responsable:"Yussep Felipe Pinzón", correo:"sanbartolome@aldimark.com"},
            {centro:"Coopidrogas", responsable:"Ricardo Gomez", correo:"coopidrogas@aldimark.com"},
            {centro:"Crown Colombiana", responsable:"Ingrid Julieth Olarte", correo:"crown@aldimark.com"},
            {centro:"Doria", responsable:"Rubiela Mateus", correo:"doria@aldimark.com"},
            {centro:"Eurofarma", responsable:"Marcela Jineth Huerfano", correo:"eurofarma@aldimark.com"},
            {centro:"Fiberglass", responsable:"Lina Maybeth Bohorquez", correo:"fiberglass@aldimark.com"},
            {centro:"Fucs", responsable:"Irene Astrid Alfonso", correo:"hscentro@aldimark.com"},
            {centro:"Gimnasio Guilford", responsable:"Luis Mauricio Vargas", correo:"guilford@aldimark.com"},
            {centro:"Gimnasio Jose Joaquin Casas", responsable:"Carolina Ortiz", correo:"jjcasas@aldimark.com"},
            {centro:"Granitos y Marmoles", responsable:"Yussep Felipe Pinzón", correo:"granitosymarmoles@aldimark.com"},
            {centro:"Helicentro", responsable:"Yipsi Astrid Gonzalez", correo:"helicentro@aldimark.com"},
            {centro:"Hospital San Jose Centro", responsable:"Irene Astrid Alfonso", correo:"hscentro@aldimark.com"},
            {centro:"Hospital San Jose Infantil", responsable:"Maria del Rosario Morales", correo:"hsanjose@aldimark.com"},
            {centro:"Hospital San Carlos", responsable:"Fabio Valdeblanquez", correo:"sancarlos@aldimark.com"},
            {centro:"Hunter Douglas", responsable:"Yipsi Astrid Gonzalez", correo:"hunterdouglas@aldimark.com"},
            {centro:"Ikea Cedi", responsable:"Angie Natalia Neme", correo:"ikea@aldimark.com"},
            {centro:"Kellogg's - Kellanova", responsable:"Jose Armando Montaña", correo:"kellanova@aldimark.com"},
            {centro:"Universidad Sanitas", responsable:"Zully Muñoz", correo:"unisanitas@aldimark.com"}
        ]
    };

   function actualizarCentros() {
    const area = document.getElementById("area").value;
    const select = document.getElementById("centro_costo");
    const responsable = document.getElementById("responsable");
    const correo = document.getElementById("correo");

    select.innerHTML = "";
    responsable.value = "";
    correo.value = "";

    // Opción vacía por defecto
    const emptyOption = document.createElement("option");
    emptyOption.value = "";
    emptyOption.text = "Seleccione un centro de costo";
    emptyOption.selected = true;
    // emptyOption.disabled = true;
    select.appendChild(emptyOption);

    if(centros[area]){
        centros[area].forEach(c => {
            const option = document.createElement("option");
            option.value = c.centro;
            option.text = c.centro;
            select.appendChild(option);
        });
    }
}

    function actualizarResponsable() {
        const area = document.getElementById("area").value;
        const centro = document.getElementById("centro_costo").value;
        const responsable = document.getElementById("responsable");
        const correo = document.getElementById("correo");

        const obj = centros[area].find(c => c.centro === centro);
        if(obj){
            responsable.value = obj.responsable;
            correo.value = obj.correo;
        }
    }

   function limpiarFormulario() {
    // No limpiar mes ni fecha
    const area = document.getElementById("area");
    area.value = "";
    const centro = document.getElementById("centro_costo");
    centro.innerHTML = '<option value="" selected>Seleccione un centro de costo</option>';
    document.getElementById("responsable").value = "";
    document.getElementById("correo").value = "";
    document.getElementById("tema").selectedIndex = 0;
    document.getElementsByName("solicitud")[0].value = "";
}

    window.onload = function(){
        actualizarCentros();
        document.getElementById("area").addEventListener("change", actualizarCentros);
        document.getElementById("centro_costo").addEventListener("change", actualizarResponsable);
        limpiarFormulario();
    };

    // Limpiar formulario después de enviar (si el mensaje de éxito aparece)
    document.addEventListener("DOMContentLoaded", function() {
        var mensaje = document.querySelector('.mensaje');
        if(mensaje && mensaje.textContent.includes('exitosamente')) {
            setTimeout(limpiarFormulario, 100); // Pequeño delay para asegurar render
        }
    });
</script>
</head>
<body>
<div class="container">
<div id="modal-mensaje" style="display:none;">
    <div class="modal-contenido">
        <span id="cerrar-modal" style="cursor:pointer;font-size:24px;float:right;">&times;</span>
        <div id="texto-modal"></div>
    </div>
</div>
    <h1><img src="img/logo.png" alt="Aldimark" style="max-width:150px;"></h1>
    <form method="POST">
        <label>Mes:</label>
        <input type="text" name="mes" value="<?php echo date('F'); ?>" readonly required>

        <label>Fecha:</label>
        <input type="text" name="fecha" value="<?php echo date('Y-m-d'); ?>" readonly required>

        <label>Área:</label>
        <select name="area" id="area" required>
            <option value="" selected disabled>Seleccione un área</option>
            <option value="ADM">ADM</option>
            <option value="CDS">CDS</option>
        </select>

        <label>Centro de Costo:</label>
        <select name="centro_costo" id="centro_costo" required></select>

        <label>Responsable:</label>
        <input type="text" name="responsable" id="responsable" readonly required>

        <label>Correo:</label>
        <input type="text" name="correo" id="correo" readonly required>

        <label>Tema:</label>
        <select name="tema" id="tema" required>
            <option value="" selected disabled>Seleccione un tema</option>
            <option value="SOLICITUD PARA EQUIPOS">SOLICITUD PARA EQUIPOS</option>           
            <option value="CAMBIO DE EQUIPOS">CAMBIO DE EQUIPOS</option>
            <option value="ARREGLO DE EQUIPOS">ARREGLO DE EQUIPOS</option>
            <option value="SOFTWARE">SOFTWARE (office , anydesk , otros)</option>
            <option value="MANTENIMIENTO IMPRESORAS">MANTENIMIENTO IMPRESORAS</option>
            <option value="CONFIGURACION IMPRESORAS">CONFIGURACION IMPRESORAS</option>
            <option value="EQUIPOS BIOMETRICOS ( huelleros , lectores RFid , pistolas escaner , otros )">EQUIPOS BIOMETRICOS ( huelleros , lectores RFid , pistolas escaner , otros )</option>
            <option value="SOFTWARE BIOMETRICOS ( Paygo , Vloki , Mcs tech )">SOFTWARE BIOMETRICOS ( Paygo , Vloki , Mcs tech )</option>
            <option value="SOFTWARE IMPRESORAS">SOFTWARE IMPRESORAS</option>
            <option value="REDES LOCAL">REDES LOCAL</option>
            <option value="REDES MOVIL">REDES MOVIL</option>
            <option value="FIRMAS CORREOS">FIRMAS CORREOS</option>
            <option value="SIESA">SIESA</option>
            <option value="INFORMES - ACTAS REUNION">INFORMES - ACTAS REUNION</option>
            <option value="REVISION DE INFORMACION">REVISION DE INFORMACION</option>
            <option value="COTIZACIONES">COTIZACIONES</option>
            <option value="LINKS - PAG WEB - NUBES">LINKS - PAG WEB - NUBES</option>
            <option value="SOLICITUDES AREAS">SOLICITUDES AREAS</option>
            <option value="ADECUACIONES LOCATIVAS">ADECUACIONES LOCATIVAS</option>
        </select>

        <label>Solicitud:</label>
        <textarea name="solicitud" required maxlength="500"></textarea>

        <input type="hidden" name="estado" value="Pendiente">

        <button type="submit" class="btn-enviar">Enviar Ticket</button>
    </form>
<?php if($mensaje){ ?>
<script>
window.onload = function() {
    limpiarFormulario();
    mostrarModal(`<?php echo addslashes($mensaje); ?>`);
    // Limpiar mensaje de la URL para evitar que reaparezca al refrescar
    if(window.history.replaceState){
        const url = new URL(window.location);
        url.searchParams.delete('mensaje');
        window.history.replaceState({}, document.title, url.pathname);
    }
};
</script>
<?php } ?>
    <br>
    <button type="button" class="btn-admin" onclick="window.location.href='admin.php'">Entrar como Admin</button>
</div>
</body>
</html>