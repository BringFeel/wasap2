<link rel="stylesheet" href="https://wasap2.bringfeel.com.ar/style/css/header.css">
<?php

session_start();

if(!isset($_SESSION['usuario'])){
	header("location: https://wasap2.bringfeel.com.ar");
}

?>
<header>
    <div class="logo">
        <h1><a href="https://wasap2.bringfeel.com.ar/wasap/chat" style="color: white;text-decoration: none;">WASAP2</a></h1>
    </div>
    <div class="nav">
      <ul>
        <li id="show-nails">
            <a>Cuenta</a>
            <div class="thumbnail-peekaboo">
                <a href="https://wasap2.bringfeel.com.ar/wasap/account/edit?color=<?php echo $_SESSION['userColor']; ?>" id="editAccount">Editar Cuenta</a>
                <a href="https://wasap2.bringfeel.com.ar/auth/logout">LogOut (<?php $usuario = $_SESSION['usuario']; echo"$usuario"; ?>)</a>
            </div>
          </li>
      </ul>
    </div>
  </header>