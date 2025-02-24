<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/css/chat.css">
    <link rel="shortcut icon" href="https://wasap2.bringfeel.com.ar/style/img/favicon.jpg" type="image/x-icon" />
    <title>Chat Global</title>
</head>
<body>
    <?php require "../style/header/header.php"; ?>

    <style>
      #below-header {
        margin-top: 120px;
      }

      #below-header #left, #below-header #playerlist {
        text-align: left;
      }

      #below-header #right {
        text-align: right;
      }

      #connection-status {
        color: red;
      }
    </style>

    <div id="main">

    <div id="below-header">
    <h4 id="right">ESTADO DE LA CONEXIÓN</h4>
          <div id="right">
            <li><span id="connection-status">Desconectado</span> | <span id="connection-latency">0</span>ms</li>
          </div>
    </div>

      <div id="below-header">
          <h4 id="left">USUARIOS ONLINE</h4>
          <div id="playerlist">
          </div>
      </div>

        <div id="centrado">
        <h1>Chat Global</h1>
        <div id="messages"></div>
        <div id="input-area">
        <form id="form" action="">
            <input type="text" id="message-input" placeholder="Escribe tu mensaje aquí..." maxlength="40">
            <button id="send-button">Enviar</button>
        </form>
        </div>
    </div>
    </div>
</body>
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
  <script>
    var username = "<?php echo $_SESSION['usuario']; ?>";
    var userColor = "<?php echo $_SESSION['userColor']; ?>";

    var socket = io("https://wss.bringfeel.com.ar");

    var form = document.getElementById('form');
    var input = document.getElementById('message-input');
    var messages = document.getElementById('messages');
    const connectionStatus = document.getElementById('connection-status');
    const connectionLatency = document.getElementById('connection-latency');

    function timeConverter(UNIX_timestamp){
        var a = new Date(UNIX_timestamp * 1000);
        var months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var day = days[a.getDay()];
        var date = a.getDate();
        var hour = a.getHours() < 10 ? `0${a.getHours()}` : a.getHours();
        var min = a.getMinutes() < 10 ? `0${a.getMinutes()}` : a.getMinutes();
        var sec = a.getSeconds() < 10 ? `0${a.getSeconds()}` : a.getSeconds();
        var time = day + ", " + date + ' de ' + month + ' de ' + year + ' ' + hour + ':' + min + ':' + sec ;
      return time;
    }

      /**
    * Represents a book.
    * @constructor
    * @param {string} msg - Message
    * @param {string} user - Username
    * @param {string} color - Color of Username
    * @param {bool} isOwner - If sender is owner
    */

    function createMessage (msg, user, color, isOwner) {
      //Creación de los Elementos a Usar
        var item = document.createElement('li');
        var message = document.createElement('span');
        var separator = document.createElement('span');
        var nameContainer = document.createElement('span');
        var name = document.createElement('a');

      //Dando propiedades y fijando las ubicaciones
      //de cada elemento que compone el mensaje
        message.textContent = msg;
        separator.textContent = " : ";
        nameContainer.appendChild(document.createTextNode("<"));
        name.textContent = user;
        name.style.color = color;
          nameContainer.appendChild(name);
          nameContainer.appendChild(document.createTextNode(">"));

      //Agregando en orden el mensaje y el nombre de usuario
      //Dependiendo si se está desde la vista del remitente
      //o el destinatario
        isOwner ? item.appendChild(message) : item.appendChild(nameContainer); // hola / pancho
        item.appendChild(separator); // :
        isOwner ? item.appendChild(nameContainer) : item.appendChild(message); // pancho / hola

      //Propiedades finales del contenedor
        item.setAttribute('title', timeConverter(Math.floor(new Date().getTime() / 1000)));
        messages.appendChild(item);
        item.style.textAlign = isOwner ? "right" : "left";
    }

    socket.on('connect', function() {
      connectionStatus.textContent = "Conectado";
      connectionStatus.style.color = "#19ff00";
      if (socket.connected) {
        setInterval(() => {
            const start = Date.now();

          socket.emit("ping", () => {
            const duration = Date.now() - start;
            connectionLatency.textContent = duration;
          });

          socket.emit("userOnline", username);
        }, 1000);
      }
    });

    socket.on('disconnect', ()=>{
      connectionStatus.textContent = "Desconectado";
      connectionStatus.style.color = "red";
      connectionLatency.textContent = 0;
    });

    form.addEventListener('submit', function(e){
      e.preventDefault();
      if(input.value){
        socket.emit('chat message', input.value, "<?php $usuario = $_SESSION['usuario']; echo"$usuario";?>", userColor);
        input.value = '';
      }
    })

    socket.on('playerlist', function(players) {
        var playersList = document.getElementById('playerlist');
        if (!playersList) {
            console.error("El elemento playerslist no existe en el DOM.");
            return;
        }
        playersList.innerHTML = ''; // Limpiar lista actual
        players.forEach(player => {
            var item = document.createElement('li');
            item.textContent = player;
            playersList.appendChild(item);
        });
    });

    socket.on('chat message', function(msg, user, color){
      createMessage(msg, user, color, user === username);

      messages.scrollTop = messages.scrollHeight;
    })
  </script>
</html>