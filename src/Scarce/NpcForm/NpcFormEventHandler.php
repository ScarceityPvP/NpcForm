<?php

namespace Scarce\NpcForm;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\NpcRequestPacket;
use pocketmine\Server;
use Scarce\NpcForm\Entities\Npc;

class NpcFormEventHandler implements Listener{

    public function __construct()
    {
    }

    /**
     * Player name, {@link NpcRequestPacket->actionIndex}.
     * @var array<string, int>
     */
    private array $npc;

    public function DataPacketReceive(DataPacketReceiveEvent $event) : void {
        $pk = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();


        if ($pk instanceof NpcRequestPacket){
            if ($player === null) {
                Server::getInstance()->getLogger()->warning("Connection {$event->getOrigin()->getDisplayName()} might be abusing NpcRequestPacket.");
                return;
            }

            if (($entity = Server::getInstance()->getWorldManager()->findEntity($pk->actorRuntimeId)) === null){
                return;
            }
            if (!$entity instanceof Npc){
                return;
            }
            switch ($pk->requestType){
                case NpcRequestPacket::REQUEST_EXECUTE_ACTION:
                $this->npc[$player->getName()] = $pk->actionIndex;
                break;
                case NpcRequestPacket::REQUEST_EXECUTE_CLOSING_COMMANDS:
                if (isset($this->npc[$player->getName()])){
                    $response = $this->npc[$player->getName()];
                    unset($this->npc[$player->getName()]);
                    $entity->handleResponse($player, $response);
                    break;
                }
            }
        }

    }
}
