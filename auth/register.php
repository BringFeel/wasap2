<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        ////////////// CONEXIÓN A LA BASE DE DATOS Y VERIFICACIÓN DE USUARIO //////////////
        require '../database/conexion.php'; //Conexión a la base de datos
        session_start();
        $usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (strpos($usuario, ' ') !== false) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=Tu usuario no puede contener espacios.');
        if (strlen($usuario) < 4) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=Tu usuario debe tener al menos 4 caracteres.');
        if (strlen($usuario) > 16) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=Tu usuario debe tener menos de 16 caracteres.');
        ///////////////// VERIFICA QUE EL USUARIO NO CONTENGA ESPACIOS Y SEA MAYOR A 4 / MENOR A 16 DÍGITOS /////////////////

        //////////////// SE VERIFICA SI EL USUARIO YA EXISTE //////////////////
        $verifyUser = $conexion->prepare("SELECT EXISTS(SELECT 1 FROM users WHERE username = ?) AS user_exists");
        $verifyUser->execute([$usuario]);
        $user_exists = $verifyUser->fetchColumn();
        if ($user_exists) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=El usuario ya existe.');
        //////////////// EN CASO DE QUE EXISTA SE TERMINA EL PROCESO //////////////

        //////////////// SE VERIFICA QUE LA CONTRASEÑA NO CONTENGA ESPACIOS Y SEA MAYOR A 8 DÍGITOS //////////////
            $contraPelada = filter_var($_POST['clave'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (strpos($contraPelada, ' ') !== false) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=Tu contraseña no puede contener espacios.');
            if (strlen($contraPelada) < 8) return header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=La contraseña debe tener al menos 8 caracteres.');

        ////////////// COMIENZA EL PROCESO PARA AGREGAR AL USUARIO A LA DB //////////////
            $contraYSalt = $contraPleada . $usuario;
            $clave = password_hash($contraYSalt, PASSWORD_DEFAULT);

            $datetime = new DateTime();
            $formattedDatetime = $datetime->format('Y-m-d H:i:s.u');

            $consulta=$conexion->prepare("INSERT INTO `users` (`username`, `password`, `dateCreated`) VALUES (:username, :password, :dateCreated)");
            $consulta->bindParam(':username', $usuario);
            $consulta->bindParam(':password', $clave);
            $consulta->bindParam(':dateCreated', $formattedDatetime);

            if ($consulta -> execute()) { //Si la consulta se ejecuta correctamente se envía al usuario al chat.
                $_SESSION['usuario']  = $usuario;
                $_SESSION['userColor'] = "#ffffff";
                header('location:https://wasap2.bringfeel.com.ar/wasap/chat');
            }

    } catch (PDOException $e) { //En el caso de que se vaya todo a la mierda se manejan los errores con esto
        if ($e->errorInfo[1] == 1062) {
            header('location:https://wasap2.bringfeel.com.ar/register?fail=true&error=El usuario ya existe.');
        } else {
            $errorMessage = $e->getMessage(); //Agarro el error
            header('Location:https://wasap2.bringfeel.com.ar/register?fail=true&error='.$errorMessage); //Me lo paso por el culo
        }
    }
} else {
    header('Location:https://wasap2.bringfeel.com.ar/register?fail=true&error=No se envió ningún dato.'); //Algún mogólico quiso abrir el "register.php" pelado
}
?>