<?php

namespace Scarce\NpcForm;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\NpcRequestPacket;
use pocketmine\Server;
use Scarce\NpcForm\Entities\Npc;

class NpcFormEventHandler implements Listener{

    public function __construct()
    {
    }

    public static $skin = null;

    private $npc;

    public function DataPacketReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();

        if ($pk instanceof NpcRequestPacket){
            if (($entity = Server::getInstance()->findEntity($pk->entityRuntimeId)) === null){
                return;
            }
            if (!$entity instanceof Npc){
                return;
            }
            switch ($pk->requestType){
                case NpcRequestPacket::REQUEST_EXECUTE_ACTION:
                    $this->npc[$player->getName()] = $pk->actionType;
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

    public function PlJ(PlayerJoinEvent $event){
        self::$skin = $event->getPlayer()->getSkin();
    }
}
