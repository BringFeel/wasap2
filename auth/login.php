<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        require '../database/conexion.php';
        session_start();
        $usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strpos($usuario, ' ') !== false) return header('location:https://wasap2.bringfeel.com.ar/?fail=true&error=Tu usuario no puede contener espacios.');
        if (strlen($usuario) < 4) return header('location:https://wasap2.bringfeel.com.ar/?fail=true&error=Tu usuario debe tener al menos 4 caracteres.');
        if (strlen($usuario) > 16) return header('location:https://wasap2.bringfeel.com.ar/?fail=true&error=Tu usuario debe tener menos de 16 caracteres.');

        $contraPelada = filter_var($_POST['clave'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (strpos($contraPelada, ' ') !== false) return header('location:https://wasap2.bringfeel.com.ar/?fail=true&error=Tu contraseña no puede contener espacios.');
        if (strlen($contraPelada) < 8) return header('location:https://wasap2.bringfeel.com.ar/?fail=true&error=La contraseña debe tener al menos 8 caracteres.');

        ////////////// Consulta SQL //////////////
        $consulta = $conexion->prepare("SELECT users.username, users.password, config.color
            FROM users
            INNER JOIN config ON users.username = config.username
            WHERE users.username = :username;"); //Se prepara la consulta
        $consulta->bindParam(':username', $usuario); //Se establece el parámetro
        $consulta->execute(); //Se ejecuta la consulta
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC); //Se transforma la respuesta en una matriz
        $hashAlmacenado = $datos[0]['password']; //Se busca el hash de la contraseña
        ////////////// Consulta SQL //////////////
        $contraYSalt = $contraPelada . $usuario;
        echo $contraYSalt;

        if ($hashAlmacenado && password_verify($contraYSalt, $hashAlmacenado)) {
            $_SESSION['usuario']  = $usuario;
            $_SESSION['userColor'] = $datos[0]['color'];
            header('Location:https://wasap2.bringfeel.com.ar/wasap/chat');
        } else {
            header('Location:https://wasap2.bringfeel.com.ar/?fail=true&error=Clave o Usuario Incorrecto.');
        }
    } catch (PDOException $e) { //En el caso de que se vaya todo a la mierda se manejan los errores con esto
        $errorMessage = $e->getMessage(); //Agarro el error
        header('Location:https://wasap2.bringfeel.com.ar/?fail=true&error=' . $errorMessage); //Me lo paso por el culo
    }
} else {
    header('Location:https://wasap2.bringfeel.com.ar/?fail=true&error=No se envió ningún dato.'); //Algún mogólico quiso abrir el "register.php" pelado
}
