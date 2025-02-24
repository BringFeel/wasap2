<?php

if(!isset($_SESSION['usuario'])){
    header("location:https://wasap2.bringfeel.com.ar/");
}

// Iniciamos la sesion
session_start();

// Destruir todo en esta sesión
session_destroy();

header('Location:https://wasap2.bringfeel.com.ar');
?>