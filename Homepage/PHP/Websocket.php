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
        public function sendMessage($msg, $recevor){
            foreach($this->clients as $client){
                if($client == $recevor){
                    $client->send("$msg");
                }
            }
        }
        // edit function to treat message
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