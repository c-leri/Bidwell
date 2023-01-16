<?php
namespace Bidwell\Server;

use Bidwell\Model\Utilisateur;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServer implements MessageComponentInterface
{
    private \SplObjectStorage $clients;

    private array $codes;

    private array $users;

    public function start($port)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this
                )
            ),
            $port
        );
        $server->run();
    }

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->codes = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // on ajoute la connection à la liste de connexion
        $this->clients->attach($conn);  
        echo "New connection! ({$conn->resourceId})\n";

        // on génére un code aléatoire et on l'envoie au client dans un message de demande de connexion
        $this->codes[$conn->resourceId] = rand();
        $login = '{"type": "login", "code": "' . $this->codes[$conn->resourceId] . '"}';
        $conn->send($login);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        try {
            // on décode le message
            $message = json_decode($msg);
            var_dump($message);

            // si le message est de type login, on procède à l'authentification
            if (isset($message->type) && $message->type == 'login') {
                // on vérifie que le code du message correspond au code attribué à cette connexion
                if ($message->code == $this->codes[$from->resourceId]) {
                    try {
                        // on stock dans le tableau l'utilisateur correspondant au login contenu dans le message
                        $this->users[$from->resourceId] = Utilisateur::read($message->text);
                        // on a plus besoin de stocker le code de connexion de l'utilisateur
                        unset($this->codes[$from->resourceId]);
                        // on notifie l'utilisateur de la réussite de la connexion
                        $from->send('{"type": "message", "text": "Connection réalisé avec succès!"}');
                    } catch (\Exception $e) {
                        echo "Problème lors de la récupération de l'utilisateur : " . $e->getMessage();
                    }
                // si le code n'est pas bon on deconnecte l'utilisateur
                } else {
                    unset($this->codes[$from->resourceId]);
                    $from->close();
                }
            // on deconnecte l'utilisateur si il n'est pas login et qu'il essaye d'envoyer un message
            } else if (!isset($this->users[$from->resourceId])) {
                unset($this->codes[$from->resourceId]);
                $from->close();
            // si le message n'a pas de type, il n'est pas conforme, erreur
            } else if (!isset($message->type)) {
                echo "Message non conforme envoyé par la connection {$from->resourceId}";
            } else {
                
            }
        } catch (\Exception) {
            echo "Problème lors de la lecture du message envoyé par la connection {$from->resourceId}";
        } catch (\Error) {
            echo "Problème lors de la lecture du message envoyé par la connection {$from->resourceId}";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->codes[$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occured: {$e->getMessage()}\n";
        unset($this->codes[$conn->resourceId]);
        unset($this->users[$conn->resourceId]); 
        $conn->close();
    }
}