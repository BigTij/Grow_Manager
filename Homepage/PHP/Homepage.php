<?php

  use Ratchet\MessageComponentInterface;
  use Ratchet\ConnectionInterface;
  
  require __DIR__ . '/vendor/autoload.php';
  
  class ChatServer implements MessageComponentInterface {
      protected $clients;
  
      public function __construct() {
          $this->clients = new \SplObjectStorage;
      }
  
      public function onOpen(ConnectionInterface $conn) {
          // You could extract a client ID from query parameters or a message later
          $conn->userId = uniqid("user_"); // Example user ID

          // Store the new connection in $this->clients
          $this->clients->attach($conn);
          echo "New connection: {$conn->userId} ({$conn->resourceId})\n";
      }
  
      public function onMessage(ConnectionInterface $from, $msg) {
          echo "Message from {$from->userId}: $msg\n";
          foreach ($this->clients as $client) {
              if ($from !== $client) {
                  $client->send("{$from->userId} says: $msg");
              }
          }
      }
  
      public function onClose(ConnectionInterface $conn) {
          $this->clients->detach($conn);
          echo "Connection {$conn->userId} has disconnected\n";
      }
  
      public function onError(ConnectionInterface $conn, \Exception $e) {
          echo "Error on connection {$conn->userId}: {$e->getMessage()}\n";
          $conn->close();
      }
  }
  
  $server = \Ratchet\Server\IoServer::factory(
      new \Ratchet\Http\HttpServer(
          new \Ratchet\WebSocket\WsServer(
              new ChatServer()
          )
      ),
      8080 // Port
  );
  
  echo "WebSocket server running on ws://localhost:8080\n";
  $server->run();
  
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Grow Manager</title>
    <link rel="stylesheet" href="/CSS/Homepage.css">
  </head>
  <body>
<!--------- Dropdown Menu ---------------------------------->
    <div class="dropdown">
      <button class="dropbtn">Dropdown</button>
      <div class="dropdown-content">
        <a href="Homepage.php">Homepage</a>
        <a href="../Irrigation/index.php">Irrigation</a>
        <a href="../Lighting/index.php">Lighting</a>
        <a href="../Air-Control/index.php">Air Control</a>
      </div>
    </div>
<!---------------------------------------------------------->

<!--------- Basic Irrigation info -------------------------->

    <div class="Irrigation-Box">
      <?php
        include 'Irrigation.php';
      ?>
    </div>
<!----------------------------------------------------------->

<!--------- Basic Lighting info ----------------------------->

    <div class="Lighting-Box">
      <?php
        include 'Lighting.php';
      ?>
    </div>

<!----------------------------------------------------------->

<!--------- Basic Air Control info -------------------------->

    <div class="Air-Control-Box">
      <?php 
        include 'Air-Control.php';
      ?>
    </div>

<!----------------------------------------------------------->
  </body>
</html>
