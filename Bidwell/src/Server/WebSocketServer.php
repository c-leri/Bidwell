<?php
namespace Bidwell\Server;

use Bidwell\Model\Enchere;
use Bidwell\Model\Participation;
use Bidwell\Model\Utilisateur;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

use SplObjectStorage;
use Exception;
use Error;

class WebSocketServer implements MessageComponentInterface
{
    private SplObjectStorage $clients;

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

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->codes = array();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // on ajoute la connection à la liste de connexion
        $this->clients->attach($conn);
        echo "New connection! ($conn->resourceId)\n";

        $login = '{"type": "isConnected"}';
        $conn->send($login);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            // on décode le message
            $message = json_decode($msg);

            // si le message n'a pas de type ou de valeur, il n'est pas conforme
            if (!isset($message->type) || !isset($message->value)) {
                echo "Message non conforme sans type envoyé par la connection $from->resourceId : $message\n";
            } else {
                switch ($message->type) {
                    // message indiquant si l'utilisateur est connecté
                    case 'isConnected':
                        $connected = $message->value;
                        // si l'utilisateur est connecté on demande son login
                        if ($connected) {
                            // on génére un code aléatoire puis on l'envoie au client en lui de donner son login
                            $this->codes[$from->resourceId] = rand();
                            $from->send('{"type": "login", "code": "' . $this->codes[$from->resourceId] . '"}');
                        }
                        break;
                    // message contenant le login de l'utilisateur
                    case 'login':
                        // si il n'y a pas de code ou qu'il n'est pas bon, on deconnecte l'utilisateur
                        if (!isset($message->code) || $message->code != $this->codes[$from->resourceId]) {
                            echo "La connection $from->resourceId a essayé de s'authentifier avec un code eronné";
                            $from->close();
                        } else {
                            // on retire le code de cet utilisateur du tableau, on n'en a plus besoin
                            unset($this->codes[$from->resourceId]);
                            // on essaye de récupérer l'Utilisateur correspondant au login dans la bd
                            try {
                                $this->users[$from->resourceId] = Utilisateur::read($message->value);
                            } catch (Exception $e) {
                                echo "Problème lors de la lecture de l'utilisateur de la connection $from->resourceId : {$e->getMessage()}\n";
                            }
                        }
                        break;
                    // message envoyé quand un utilisateur enchéri
                    case 'enchere':
                        if (!isset($this->users[$from->resourceId])) {
                            echo "La connection $from->resourceId a essayé de participer à une enchère alors qu'elle n'est pas connecté";
                        } else {
                            // recupère l'enchère et la participation et appel la méthode encherir() qui fais les modifications liées au fait qu'un utilisateur enchérisse
                            $enchere = Enchere::read($message->value);
                            $participation = Participation::get($enchere, $this->users[$from->resourceId]);
                            $participation->encherir();

                            // notifie tous les autres utilisateurs
                            foreach ($this->clients as $client) {
                                $client->send('{"type": "enchere", "value": { "prixRetrait": ' . $participation->getMontantDerniereEnchere() . ', "prixHaut": ' . $enchere->getPrixHaut() . ', "id":'. $enchere->getId() .'}}');
                            }
                        }
                        break;
                    default:
                        echo "Message de type non conforme envoyé par la connection $from->resourceId : $message\n";
                        break;
                }
            }
        } catch (Exception|Error) {
            echo "Problème lors de la lecture du message envoyé par la connection $from->resourceId";
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Connection $conn->resourceId has disconnected\n";
        unset($this->codes[$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occured: {$e->getMessage()}\n";
        unset($this->codes[$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        $conn->close();
    }
}