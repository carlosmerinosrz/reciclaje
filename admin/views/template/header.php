<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
    <link rel="stylesheet" href="css/style.css">
</head>
    <body>
<?php
// Compruebo si hay una sesión iniciada
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
if (isset($_SESSION['id_admin'])) {
?>
        <nav> 
            <div class="logo">
                <img src="../image/icon_reciclaje.png">
                <h1>RECICLA TU BASURA</h1>
            </div>
            <ul>
                <li>
                    <a href="index.php?controlador=ccontenedoresbasura&metodo=mostrarMenuInicial">Inicio</a>
                </li>
                <li>
                    <a href="index.php?controlador=ccontenedoresbasura&metodo=listadoContenedores">G. Contenedores</a>
                </li>
                <li>
                    <a href="index.php?controlador=cbasura&metodo=eleccionGestion">G. Basura</a>
                </li>
                <li>
                    <a href="index.php?controlador=cUsuarios&metodo=cerrarSesion" class="cerrarSesion">CERRAR SESION</a>
                </li>
            </ul>
            <div class="hamburger">
            </div>
        </nav>
        <div class="menubar">
            <ul>
                <li>
                    <a href="index.php?">Inicio</a>
                </li>
                <li>
                    <a href="index.php?controlador=ccontenedoresbasura&metodo=listadoContenedores">G. Contenedores</a>
                </li>
                <li>
                    <a href="index.php?controlador=cbasura&metodo=eleccionGestion">G. Basura</a>
                </li>
                <li>
                    <a href="index.php?controlador=cUsuarios&metodo=cerrarSesion">CERRAR SESION</a>
                </li>
            </ul>
        </div>
        <div>
        <?php

}

?>