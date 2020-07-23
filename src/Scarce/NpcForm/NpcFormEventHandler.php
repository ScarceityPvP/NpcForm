<?php

namespace Scarce\NpcForm;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\NpcRequestPacket;
use pocketmine\Server;
use Scarce\NpcForm\Entity\BaseEntity;

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
            if (!$entity instanceof BaseEntity){
                return;
            }
            switch ($pk->requestType){
                case NpcRequestPacket::REQUEST_EXECUTE_ACTION:
                    $this->npc[$player->getName()] = $pk->actionType;
                    break;
                case NpcRequestPacket::REQUEST_EXECUTE_CLOSING_COMMANDS:
                    $entity->onClose($player);
                    if (isset($this->npc[$player->getName()])){
                        $response = $this->npc[$player->getName()];
                        unset($this->npc[$player->getName()]);
                        $entity->handleResponse($player, $response);
                        break;
                    }
            }
        }

    }

    public function InteractRecieve(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        if ($pk instanceof InteractPacket){
            if ($pk->action === InteractPacket::ACTION_OPEN_NPC){
                var_dump("1");
            }
        }
    }


    public function onDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        if ($entity instanceof BaseEntity && !$entity->canTakeDamage()){
            $event->setCancelled(true);
        }
    }
}
