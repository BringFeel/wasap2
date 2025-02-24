<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        require '../database/conexion.php'; //Conexión a la base de datos
        session_start();

        function changePassword($password, $username, $conexion, $TIcolor)
        {
            try {
                if (strpos($password, ' ') !== false) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=Tu contraseña no puede contener espacios.');
                if (strlen($password) < 8) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=La contraseña debe tener al menos 8 caracteres.');
                $password2 = $password . $username;

                $passwordHASH = password_hash($password2, PASSWORD_DEFAULT);

                $stmt = $conexion->prepare("UPDATE users SET password = :password WHERE username = :username");

                $stmt->bindParam(':password', $passwordHASH, PDO::PARAM_STR);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);

                $stmt->execute();
                if (!$TIcolor) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=good&message=Se cambió tu contraseña.&color=' . $_SESSION['userColor']);
            } catch (PDOException $e) {
                echo "Error en la conexión o consulta: " . $e->getMessage();
            }
        }

        function changeColor($color, $usuario, $conexion, $TIpassword)
        {
            try {
                $stmt = $conexion->prepare("UPDATE config SET color = :color WHERE username = :username");

                $stmt->bindParam(':color', $color, PDO::PARAM_STR);
                $stmt->bindParam(':username', $usuario, PDO::PARAM_STR);

                $stmt->execute();
                $_SESSION['userColor'] = $color;
                if (!$TIpassword) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=good&message=Se cambió tu color.&color=' . $color);
            } catch (PDOException $e) {
                echo "Error en la conexión o consulta: " . $e->getMessage();
            }
        }

        function convertToHex($color)
        {
            // Si el color es en formato RGB
            if (preg_match('/rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)/', $color, $matches)) {
                $r = intval($matches[1]);
                $g = intval($matches[2]);
                $b = intval($matches[3]);

                // Asegurarse de que los valores estén en el rango de 0 a 255
                if ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255) {
                    return sprintf("#%02x%02x%02x", $r, $g, $b); // Convertir a formato hexadecimal
                } else {
                    return false; // Valores fuera de rango
                }
            }

            // Si el color no está en formato RGB ni hexadecimal, retornar false
            return false;
        }

        $TIpassword = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $TIcolor = isset($_POST['color']);

        if (!$TIcolor && !$TIpassword) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=Flaco no mandaste nada.');

        $usuario = $_SESSION['usuario'];

        if ($TIpassword) changePassword($TIpassword, $usuario, $conexion, $TIcolor);

        $color = $_POST['color'];
        if ($color == $_SESSION['userColor'] && !$TIpassword) return header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=Flaco no podes ponerte el mismo color.');
        if (preg_match('/^#[a-fA-F0-9]{6}$/', $color)) changeColor($color, $usuario, $conexion, $TIpassword);
        else if (convertToHex($color)) changeColor(convertToHex($color), $usuario, $conexion, $TIpassword);

        if ($TIcolor && $TIpassword) return header("location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=good&message=Se cambiaron los datos.&color=" . $color);
    } catch (PDOException $e) { //En el caso de que se vaya todo a la mierda se manejan los errores con esto
        if ($e->errorInfo[1] == 1062) {
            header('location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=Ni idea.'); //!!
        } else {
            $errorMessage = $e->getMessage(); //Agarro el error
            header('Location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=' . $errorMessage); //Me lo paso por el culo //!!
        }
    }
} else {
    header('Location:https://wasap2.bringfeel.com.ar/wasap/account/edit?status=bad&message=No se envió ningún dato.'); //Algún mogólico quiso abrir el "register.php" pelado //!!
}
