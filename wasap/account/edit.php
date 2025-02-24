<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://wasap2.bringfeel.com.ar/style/css/chat.css">
    <link rel="shortcut icon" href="https://wasap2.bringfeel.com.ar/style/img/favicon.jpg" type="image/x-icon" />
    <title>Editar Cuenta</title>
    <style>
        body {
            background-color: #181a1b;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            text-align: center;
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
        }
        input {
            background-color: #222;
            color: white;
        }

        input[type=color] {
            height: 40px;
        }
        input[type="color"],
        input[type="password"] {
            width: calc(100% - 20px);
        }
        button {
            background-color: #0a4429;
            color: white;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #0c5a38;
        }

        #message {
            margin: 20px 200px 20px 200px;
            opacity: 0;
        }
    </style>
</head>
<body>
<?php require "../../style/header/header.php"; ?>

    <div class="container">
        <h2>Configuración de Cuenta</h2>
        <p id="message">---</p>
        <form action="https://wasap2.bringfeel.com.ar/auth/accountManager" method="post">
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingrese nueva contraseña">

            <label for="color">Color del Usuario</label>
            <input type="color" id="color" name="color">

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
<script src="https://bringfeel.com.ar/assets/js/jquery.min.js"></script>
<script>
    const params = new URLSearchParams(window.location.search);
    var colorInput = document.getElementById('color');

    const hash = window.location.hash;

    colorInput.value = hash ? hash : "#ffffff";

    if (params.get('status') === 'good') {
        const errorElement = document.getElementById('message');
        const errorMessage = params.get('message');
        if (errorElement) {
            errorElement.style.opacity = 1;
            errorElement.style.color = "#00df56";
            errorElement.innerText = errorMessage;
        }
    } else if (params.get('status') === 'bad') {
        const errorElement = document.getElementById('message');
        const errorMessage = params.get('message');
        if (errorElement) {
            errorElement.style.opacity = 1;
            errorElement.style.color = "#df0000";
            errorElement.innerText = errorMessage;
        }
    }else {
        const errorElement = document.getElementById('messgae');
        if (errorElement) {
            errorElement.style.opacity = 0;
        }
    }

    $(function() {
    $('#password').on('keypress', function(e) {
        if (e.which == 32) return false;
    });
    });
</script>
</html>
