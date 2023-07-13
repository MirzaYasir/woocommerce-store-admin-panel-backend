<?php

/**
 * Plugin Name: My WebSocket Server Plugin
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require_once 'WooCommerceStoreController.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class MyWebSocketServer implements MessageComponentInterface
{
    protected $clients;
    private $woocommerceController;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->woocommerceController = WooCommerceStoreController::getInstance();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New client connected!\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        $endpoint = $request['url'];
        $params = $request['params'];
        $type = $request['type'];
        if ($type == "GET") {
            $response = $this->woocommerceController->getRequest($endpoint, $params);
            $from->send(json_encode($response));
        } else if ($type == "POST") {
            echo "\nElse case POST request called...\n";
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Client disconnected!\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

function start_websocket_server()
{
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new MyWebSocketServer()
            )
        ),
        8080
    );

    echo "Starting WebSocket server...\n";
    $server->run();
}

start_websocket_server();
