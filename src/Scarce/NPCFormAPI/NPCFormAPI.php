<?php


namespace Scarce\NPCFormAPI;

use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use Scarce\NPCFormAPI\Entities\Npc;

class NPCFormAPI extends PluginBase{

    public function onEnable()
    {
        Entity::registerEntity(Npc::class, true);
        $this->getServer()->getPluginManager()->registerEvents(new  EventListener(), $this);
    }

}
